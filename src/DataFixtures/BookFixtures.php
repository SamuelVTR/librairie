<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 30; $i ++) {



            $author= new Author();
            $book = new Book();

            $author->setFirstName($faker->firstName);
            $author->setLastName($faker->lastName);

            $book->setAuthorId($author);

            $book->setIsRented(false);
            $book->setReference($faker->isbn10);

            $manager->persist($author);

            $manager->persist($book);

        }
        $manager->flush();
    }
}
