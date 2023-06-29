<?php

namespace App\DataFixtures;

use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $offer = new Offer();
        $offer->setType('Monthly');
        $offer->setPrice(29.99);
        $manager->persist($offer);

        $s_offer = new Offer();
        $s_offer->setType('Yearly');
        $s_offer->setPrice(299.99);
        $manager->persist($s_offer);
        $manager->flush();
    }
}
