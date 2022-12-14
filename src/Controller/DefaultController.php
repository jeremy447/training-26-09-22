<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_default_index")
     */
    public function index(MovieRepository $repository): Response
    {
        return $this->render('default/index.html.twig', [
            'movies' => $repository->findBy([], ['id' => 'DESC'], 6),
        ]);
    }

    /**
     * @Route("/contact", name="app_default_contact")
     */
    public function contact(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'contact',
        ]);
    }
}
