<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    #[Route('/checkout/{id}', name: 'app_checkout')]
    public function index(Offer $offer, OfferRepository $offerRepository): Response
    {
        $offer = $offerRepository->find($offer->getId());

        return $this->render('checkout/index.html.twig', [
            'offer' => $offer,
        ]);
    }


}
