<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book/post", name="app_book_post")
 */
class PostBookController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'PostBookController',
        ]);
    }
}