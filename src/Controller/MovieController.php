<?php

namespace App\Controller;

use App\Notifier\MovieNotifier;
use App\Provider\MovieProvider;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie", name="app_movie_")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(MovieNotifier $notifier): Response
    {
        $notifier->sendNotification('FINALLY!');
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="details")
     */
    public function details(int $id, MovieRepository $repository): Response
    {
        $movie = $repository->find($id);
        
        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/omdb/{title<[a-zA-Z0-9-_' ]+>}", name="omdb")
     */
    public function omdb(string $title, MovieProvider $provider): Response
    {
        return $this->render('movie/details.html.twig', [
            'movie' => $provider->getMovieByTitle($title),
        ]);
    }

    public function decades()
    {
        return $this->render('includes/_decades.html.twig', [
            'decades' => [],
        ]);
    }
}
