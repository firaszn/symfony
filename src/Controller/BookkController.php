<?php

namespace App\Controller;
use App\Entity\Author;
use App\Entity\Bookk;
use App\Form\BookkType;
use App\Repository\BookkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookkController extends AbstractController
{
    #[Route('/listbook/', name: 'list_book')]
    public function list(BookkRepository $bookkRepository, Request $request): Response
    { 
        $ref = $request->query->get('ref');
        if ($ref) {
            $books = $bookkRepository->findByRef($ref);
        } else {
            $books = $bookkRepository->findAll();
        }

        $author = $this->getDoctrine()->getRepository(Author::class)->findOneBy(['username' => 'firas44']);

        if ($author instanceof Author) {
            $shakespeareBooks = $bookkRepository->findBy(['author' => $author]);
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($shakespeareBooks as $book) {
                $book->setCategory('Romance');
                $entityManager->persist($book);
            }
            $entityManager->flush();
        }

        return $this->render('bookk/list.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/listbyauthor/', name: 'list_byauthor')]
    public function listbyauthor(BookkRepository $bookkRepository,Request $request): Response
    { 

    $sortBy = $request->query->get('sort_by', 'author'); 
    $books = $bookkRepository->findAll();
    if ($sortBy === 'author') {
        usort($books, function($a, $b) {
            return strcmp($a->getAuthor()->getUsername(), $b->getAuthor()->getUsername());
        });
    }
    return $this->render('bookk/listbyauthor.html.twig', [
        'books' => $books,
    ]);

}
    
        #[Route('/bookk/add', name: 'addbookk')]
        public function addbook(ManagerRegistry $doctrine, Request $request): Response
    {
        $bookk = new Bookk();

        $form = $this->createForm(BookkType::class, $bookk);
        $form->add('Ajouter', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();

            $author = $bookk->getAuthor();

            if ($author) {
                $author->setNbBooks($author->getNbBooks() + 1);
            }

            $em->persist($bookk);
            $em->flush();

            return $this->redirectToRoute('list_book');
        }

        return $this->render('bookk/addbookk.html.twig', [
            'formB' => $form->createView(),
        ]);
    }
    #[Route('/published-books', name: 'published_books')]
public function publishedBooks(BookkRepository $bookkRepository): Response
{
    $publishedBooks = $bookkRepository->findBy(['published' => true]);
    $unpublishedBooks = $bookkRepository->findBy(['published' => false]);

    $publishedCount = count($publishedBooks);
    $unpublishedCount = count($unpublishedBooks);

    $category = 'Science-Fiction';
    $bookCount = $bookkRepository->countBooksByCategory($category);

    return $this->render('bookk/published_books.html.twig', [
        'publishedBooks' => $publishedBooks,
        'unpublishedBooks' => $unpublishedBooks,
        'publishedCount' => $publishedCount,
        'unpublishedCount' => $unpublishedCount,
        'category' => $category,
        'bookCount' => $bookCount,
    ]);
}
#[Route('/bookk/edit/{ref}', name: 'edit_book')]
public function editBook(string $ref, ManagerRegistry $doctrine, Request $request, BookkRepository $bookkRepository): Response
{
    $bookk = $bookkRepository->findOneBy(['ref' => $ref]);
    if (!$bookk) {
        throw $this->createNotFoundException('Book not found');
    }

    $form = $this->createForm(BookkType::class, $bookk);
    $form->add('Modifier', SubmitType::class);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $doctrine->getManager();
        $em->flush();

        return $this->redirectToRoute('list_book');
    }

    return $this->render('bookk/editbookk.html.twig', [
        'formB' => $form->createView(),
    ]);
}
#[Route('/bookk/delete/{ref}', name: 'delete_book')]
public function deleteBook(string $ref, ManagerRegistry $doctrine, BookkRepository $bookkRepository): Response
{
    $em = $doctrine->getManager();
    $bookk = $bookkRepository->findOneBy(['ref' => $ref]);

    if (!$bookk) {
        throw $this->createNotFoundException('Book not found');
    }

    $em->remove($bookk);
    $em->flush();

    return $this->redirectToRoute('published_books');
}

#[Route('/bookk/showBook{ref}', name: 'show_book')]
public function showBook($ref ,  BookkRepository $bookkRepository ): Response
{ 

    $bookk = $bookkRepository->findOneBy(['ref' => $ref]);
    return $this->render('bookk/detail.html.twig', ['book' => $bookk
    ]);
}
#[Route('/showAllbooksByauthor/{id}', name: 'showbooks')]
public function showbooksByAuthor($id ,  BookkRepository $bookkRepository ): Response
{ 

    $bookk = $bookkRepository->showAllbooksByAuthor($id);
    return $this->render('bookk/show.html.twig', ['book' => $bookk]);
 
}   
#[Route('/books-between-dates', name: 'books_between_dates')]
    public function booksBetweenDates(BookkRepository $bookkRepository): Response
    {
        $startDate = new \DateTime('2014-01-01');
        $endDate = new \DateTime('2018-12-31');

        $books = $bookkRepository->findBooksBetweenDates($startDate, $endDate);

        return $this->render('bookk/books_between_dates.html.twig', [
            'books' => $books,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

       

}
