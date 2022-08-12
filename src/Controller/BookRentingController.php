<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookRenting;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookRentingController extends AbstractController
{
    #[Route('/book/renting/{id}','app_book_renting')]
    public function louerLivre(ManagerRegistry $doctrine,Book $book): Response
    {
      $Bookrenting = new BookRenting();

        $repository = $doctrine->getRepository(Book::class);
        $book = $repository->find($book->getId());
        $repository = $doctrine->getRepository(User::class);


        $user = $repository->find($this->getUser()->getUserIdentifier());


        $Bookrenting->setLimitDate(New \DateTimeImmutable());

        $Bookrenting->setRentingEnd(New \DateTimeImmutable());
        $Bookrenting->setRentingStart(New \DateTimeImmutable());


        $book->setIsRented(true);
        $Bookrenting->setBookId($book);
        $Bookrenting->setUserId($user);



            $doctrine->getManager()->persist($Bookrenting);
            $doctrine->getManager()->persist($book);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute(  'books.list');
        }

    #[Route('/book/meslivres','books.meslivres')]
    public function meslivres(ManagerRegistry $doctrine): Response
    {

        if ($this->getUser()==true)
        {

            $repository = $doctrine->getRepository(BookRenting::class);
            $booksrentings = $repository->findAll();


            return $this->render('book/mesLivres.html.twig',
                ['booksrentings' => $booksrentings]);
        }
        else
        {
            return $this->render('security/login.html.twig',
                ['error' => "Connectez voous pour accedez aux livres que vous avez loué"]);

        }


    }
    #[Route('/rendrelivre/{id}','rendrelivre')]

    public function rendrelivre(Request $request,BookRenting $bookrenting,ManagerRegistry $doctrine) :Response
    {

        $bookrenting->getBookId()->setIsRented(false);
        $doctrine->getManager()->remove($bookrenting);
        $doctrine->getManager()->flush();

        return $this->redirectToRoute(  'books.meslivres');


    }




  // delete all renting
//        $repository = $doctrine->getRepository(BookRenting::class);
//       $Bookrenting = $repository->findAll();
//
//
//       foreach ($Bookrenting as $b){
//           $doctrine->getManager()->remove($b);
//
//       }
//
//
//        $doctrine->getManager()->flush();
//        return $this->redirectToRoute(  'admin.books.list');


}
