<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book", name="app_book_")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET", "POST"})
     */
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    /**
     * @Route("/{!page<\d+>?1}", name="details")
     */
    public function details(Request $request, int $title = 1): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => $title,
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, BookRepository $repository): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->add($book, true);

            return $this->redirectToRoute('app_book_index');
        }

        return $this->renderForm('book/new.html.twig', [
            'form' => $form,
        ]);
    }
}
