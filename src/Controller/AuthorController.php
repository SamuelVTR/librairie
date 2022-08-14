<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\User;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/authors','admin.authors.list')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Author::class);

        $author= $repository->findAll();

        return $this->render('admin/author/listAuthor.html.twig',['authors' => $author]);
    }


    #[Route('/create/newauthor','create.newauthor')]
    public function createauthor(Request $request,ManagerRegistry $doctrine){

        $author = new Author();
        $form= $this->createForm(AuthorType::class,$author);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $doctrine->getManager()->persist($author);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute(  'admin.authors.list');
        }
        return $this->render('admin/author/createauthor.html.twig', [
            'author'=> $author,
            'form' => $form->createView(),
        ]);
    }


    // modifier auteur
    #[Route('/authors/edit/{id}','admin.author.edit')]
    public function Editauthor(Request $request,Author $author,ManagerRegistry $doctrine) :Response
    {

        $form= $this->createForm(AuthorType::class,$author);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $doctrine->getManager()->persist($author);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute(  'admin.authors.list');
        }

        return $this->render('/admin/author/edit.html.twig', [
            'author'=> $author,
            'form' => $form->createView()

        ]);
    }

    //supprimer un auteur
    #[Route('/authors/delete/{id}','admin.author.delete')]
    public function deleteauthor(Author $author,ManagerRegistry $doctrine) :Response
    {


        $doctrine->getManager()->remove($author);
        $doctrine->getManager()->flush();
        return $this->redirectToRoute(  'admin.authors.list');


    }
}
