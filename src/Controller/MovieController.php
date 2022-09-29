<?php

namespace App\Controller;

use App\Notifier\MovieNotifier;
use App\Provider\MovieProvider;
use App\Repository\MovieRepository;
use App\Security\Voter\MovieVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    public function index(MovieRepository $repository, MovieNotifier $notifier): Response
    {
        $notifier->sendNotification('FINALLY!');
        return $this->render('movie/index.html.twig', [
            'movies' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="details")
     */
    public function details(int $id, MovieRepository $repository): Response
    {
        $movie = $repository->find($id);
        $this->denyAccessUnlessGranted(MovieVoter::VIEW, $movie);

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/omdb/{title<[a-zA-Z0-9-_' ]+>}", name="omdb")
     */
    public function omdb(string $title, MovieProvider $provider): Response
    {
        $movie = $provider->getMovieByTitle($title);
        $this->denyAccessUnlessGranted(MovieVoter::VIEW, $movie);

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
        ]);
    }

    public function decades(MovieRepository $repository)
    {
        return $this->render('includes/_decades.html.twig', [
            'decades' => $repository->getDecades(),
        ]);
    }
}
