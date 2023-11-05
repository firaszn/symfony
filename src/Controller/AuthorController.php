<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/authorr/{name}', name: 'app_author')]
    public function index($name): Response
    { 
        $nickname = "Mr ".$name;
        $age = 55;
        return $this->render('author/index.html.twig', [
            'controller_name' => $nickname,
            'age' => $age,
        ]);
    }
    #[Route('/list/', name: 'list_author')]
    public function list(AuthorRepository $authrepo,Request $request): Response
    { 
        
        $authors =$authrepo->findAllAuthorsOrderedByEmail();
        $minBooks = $request->query->get('minBooks');
        $maxBooks = $request->query->get('maxBooks');
        $authors = $authrepo->findAuthorsByBookCountRange($minBooks, $maxBooks);


        return $this->render('author/list.html.twig', ['authors' => $authors
            
        ]);
    }
    
    #[Route('/author/add', name: 'addauthor')]
public function addauthor(ManagerRegistry $doctrine, Request $request): Response
{
    $author = new Author();

    $form = $this->createForm(AuthorType::class, $author);
    $form->add('Ajouter', SubmitType::class); // Move this line outside of the if statement

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $doctrine->getManager();
        $em->persist($author);
        $em->flush();

        return $this->redirectToRoute('list_author');
    }

    return $this->render('author/addAuthor.html.twig', [
        'formA' => $form->createView(),
    ]);
}


    #[Route('/author/{id}', name: 'showauthor')]
    public function showauthor($id , AuthorRepository $repo ): Response
    { 
    
         $author = $repo->find($id);
        return $this->render('author/detailAuthor.html.twig', ['author' => $author
        ]);
    }
    #[Route('/deleteAuthor/{id}', name: 'deleteAuthor')]
    public function deleteAuthor($id , AuthorRepository $repo , EntityManagerInterface $em ): Response
    { 
    
        $author = $repo->find($id);
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute('list_author');
     } 
    




    #[Route('/author/update/{id}', name: 'updateauthor')]
    public function updateAuthor(ManagerRegistry $doctrine, Request $request,$id): Response
    {
        $repo = $doctrine->getRepository(Author::class);
        $author = $repo->find($id);

        // Create the form
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        $form->add('Modifier',SubmitType::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($author);
            $em->flush();

            return new Response('author updated'); 
        }

        return $this->render('author/addAuthor.html.twig', [
            'formA' => $form->createView(),
        ]);
    }


}
