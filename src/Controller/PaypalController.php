<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Receipt;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaypalController extends AbstractController
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function generateToken(): mixed
    {
        $client_id = $this->params->get('paypal.client.id');
        $paypal_secret = $this->params->get('paypal.secret');
        $base_url = $this->params->get('base.url');
        $auth = base64_encode($client_id . ':' . $paypal_secret);
        $client = new Client();

        $response = $client->postAsync($base_url . '/v1/oauth2/token', [
            'headers' => [
                'Authorization' => 'Basic ' . $auth,
            ],
            'body' => 'grant_type=client_credentials',
            'method' => 'post'
        ]);
        $response = $response->wait();
        $response = json_decode($response->getBody(), true);
        return $response['access_token'];
    }

    #[Route('/create-paypal-order', name: 'create_paypal_order', methods: ['POST'])]
    public function createPaypalOrder(RequestStack $requestStack): JsonResponse
    {
        $price = json_decode($requestStack->getCurrentRequest()->getContent(), true)['sku'];
        $quantity = json_decode($requestStack->getCurrentRequest()->getContent(), true)['quantity'];
        $client = new Client();
        $access_token = $this->generateToken();
        $url = $this->params->get('base.url') . '/v2/checkout/orders';

        $response = $client->postAsync($url, [
            'method' => 'post',
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => $price,
                        ],
                        'quantity' => $quantity
                    ]
                ]
            ])
        ])->wait();
        $response = json_decode($response->getBody(), true);
        return new JsonResponse($response);

    }

    #[Route('/capture-paypal-order', name: 'capture_paypal_order', methods: ['POST'])]
    public function capturePaypalOrder(RequestStack $requestStack, EntityManagerInterface $entityManager): JsonResponse
    {
        $order_id = json_decode($requestStack->getCurrentRequest()->getContent(), true)['orderID'];
        $access_token = $this->generateToken();
        $url = $this->params->get('base.url') . '/v2/checkout/orders/' . $order_id . '/capture';
        $client = new Client();

        $response = $client->postAsync($url, [
            'method' => 'post',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ]
        ]);
        $response = json_decode($response->wait()->getBody(), true);

        if($response['status'] == 'COMPLETED'){
            $receipt = new Receipt();
            $receipt->setOrderID($order_id);
            $price = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            $receipt->setAmount($price);

            $offer_type = $entityManager->getRepository(Offer::class)->findOneBy(['price' => $price])->getType();
            $receipt->setSubscriptionType($offer_type);

            $buyer= $this->getUser();
            $receipt->setBuyer($buyer);

            $createdAt  = new \DateTime();
            $receipt->setCreatedAt($createdAt);

            $entityManager->persist($receipt);
            $entityManager->flush();
        }

        return $this->json($response);
    }


    #[Route('/paypal-success', name: 'paypal_success', methods: ['GET'])]
    public function redirectSuccess(): Response
    {
        return $this->render('paypal/payment_successful.html.twig');
    }

}
