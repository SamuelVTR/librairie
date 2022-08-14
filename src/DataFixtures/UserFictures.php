<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFictures extends Fixture
{

    // voir Appfixture.php pour le fixture des users
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

      //  $manager->flush();
    }
}
