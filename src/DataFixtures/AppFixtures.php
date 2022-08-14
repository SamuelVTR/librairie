<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
         // permet d'asher les passwords
    private $userPasswordHasherInterface;
    public function __construct (UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }
    public function load( ObjectManager $manager, ): void
    {

        $faker = Factory::create('fr_FR');
        $roles[] ='ROLE_USER';
        $rolesadmin[] ='ROLE_ADMIN';


        // création d'aumoins un utilisateur ADMIN
        // mdp : admin identifiant/phone : admin
        $user = new User();
        $user->setCreateAt(New \DateTimeImmutable());

        $user->setRoles($rolesadmin);
        $user->setLastName($faker->name);
        $user->setFirstName($faker->firstName);
        $user->setPhone("admin");

        $user->setPassword($this->userPasswordHasherInterface
            ->hashPassword($user, "admin"));

        $manager->persist($user);

        for ($i=0; $i < 30; $i ++) {

            $user = new User();
            $user->setCreateAt(New \DateTimeImmutable());

            $user->setRoles($roles);
            $user->setLastName($faker->name);
            $user->setFirstName($faker->firstName);
            $user->setPhone($faker->email);

            $user->setPassword($this->userPasswordHasherInterface->hashPassword(
                $user, $faker->password));
            $manager->persist($user);

        }


        $manager->flush();
    }

}
