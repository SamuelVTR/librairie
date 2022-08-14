<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class CreateUserController extends AbstractController
{




    #[Route('/admin','users.list')]
    public function index(ManagerRegistry $doctrine): Response
    {
      $repository = $doctrine->getRepository(User::class);

      $users= $repository->findAll();

         return $this->render('admin/index.html.twig',['users' => $users]);



    }


    #[Route('/create/newuser','create.new')]
    public function createUser(UserPasswordHasherInterface $passwordHasher,Request $request,ManagerRegistry $doctrine){

            $user = new User();
            $form= $this->createForm(UserType::class,$user);
            $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($hashedPassword);
            $user->setCreateAt(New \DateTimeImmutable());
            $roles[] ='ROLE_USER';
            $user->setRoles($roles);
            $doctrine->getManager()->persist($user);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute(  'users.list');
        }



        return $this->render('admin/users/createuser.html.twig', [
            'user'=> $user,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/admin/users/edit/{id}','admin.user.edit')]
    public function EditUser(Request $request,User $user,ManagerRegistry $doctrine) :Response
    {


        $form= $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $doctrine->getManager()->persist($user);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute(  'users.list');
        }

        return $this->render('/admin/users/edit.html.twig', [
            'user'=> $user,
            'form' => $form->createView()

        ]);
    }

    #[Route('/admin/users/delete/{id}','admin.user.delete')]
    public function deleteuser(Request $request,User $user,ManagerRegistry $doctrine) :Response
    {

            $doctrine->getManager()->remove($user);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute(  'users.list');

    }



}
