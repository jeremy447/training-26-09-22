<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello/{name<[a-zA-Z- ]+>?Bob}", name="app_hello")
     */
    public function index(string $sfVersion, string $name = 'Bob'): Response
    {
        dump($sfVersion);
        return $this->render('hello/index.html.twig', [
            'controller_name' => $name,
        ]);
    }
}
