<?php

namespace App\Controller;
use App\Entity\Author;

use App\Entity\Book;
use App\Entity\BookRenting;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

class BookController extends AbstractController
{



    // Liste pour les livres montré aux utilisateurs
    #[Route('/books', 'books.list')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Book::class);
        $books= $repository->findAll();

        $repository2 = $doctrine->getRepository(BookRenting::class);
        $bookrentings=$repository2->findAll();

        return $this->render('book/index.html.twig',
            ['books' => $books,
                'bookrentings' => $bookrentings
                ]);

    }

    #[Route('/admin/books', 'admin.books.list')]
    public function listlivre(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Book::class);
        $books= $repository->findAll();


        return $this->render('admin/books/listBook.html.twig',
            ['books' => $books]);

    }


    // création de livre  par l'admin
    #[Route('/create/book','create.book')]
    public function createBook( Request $request,ManagerRegistry $doctrine)
    {
        $repository = $doctrine->getRepository(Author::class);
        $authors= $repository->findAll();
        $book = new Book();
        $form = $this->createFormBuilder($book)
            ->add('reference', TextType::class)

            //permet d'avoir la drop down avec les nom des auteurs
            ->add('author_id', EntityType::class,  [
                'class' => Author::class,
                'choice_label' => 'last_name' ,
                'placeholder' => ' Please choose a country',
                'query_builder' => function(AuthorRepository $repository) {
             return $repository->createQueryBuilder(' c ')->orderBy('c.last_name' , 'ASC');
                },
                'constraints' => new NotBlank([ 'message' => 'Choisir un auteur'])
            ])
            ->getForm();

        $form->handleRequest($request);

        // form submit
        if ($form->isSubmitted() && $form->isValid()) {

            $doctrine->getManager()->persist($book);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('admin.books.list');
        }

        return $this->render('admin/books/createbook.html.twig', [

            'form' => $form->createView(),
        ]);

    }

    //delete book
    #[Route('/book/delete/{id}','admin.book.delete')]
    public function deleteauthor(Request $request,Book $book,ManagerRegistry $doctrine) :Response
    {

        $doctrine->getManager()->remove($book);
        $doctrine->getManager()->flush();
        return $this->redirectToRoute(  'admin.books.list');
    }


    //modifier un livre
    #[Route('/admin/book/edit/{id}','admin.book.edit')]
    public function editbook( Request $request,Book $book,ManagerRegistry $doctrine):Response
    {

        $form = $this->createFormBuilder($book)
            ->add('reference', TextType::class)
            ->add('author_id', EntityType::class,  [
                'class' => Author::class,
                'choice_label' => 'last_name' ,
                'placeholder' => ' Choisir un auteur',
                'query_builder' => function(AuthorRepository $repository) {
                    return $repository->createQueryBuilder(' c ')->orderBy('c.last_name' , 'ASC');
                },
                'constraints' => new NotBlank([ 'message' => 'Choisir un auteur'])
            ])

            ->getForm();



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $doctrine->getManager()->persist($book);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('admin.books.list');
        }

        return $this->render('admin/books/createbook.html.twig', [

            'form' => $form->createView(),
        ]);

    }

}
