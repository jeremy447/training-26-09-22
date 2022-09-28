<?php

namespace App\Provider;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Transformer\OmdbMovieTranformer;

class MovieProvider
{
    private MovieRepository $repository;
    private OmdbApiConsumer $consumer;
    private OmdbMovieTranformer $tranformer;

    public function __construct(
        MovieRepository $repository,
        OmdbApiConsumer $consumer,
        OmdbMovieTranformer $tranformer
    ) {
        $this->repository = $repository;
        $this->consumer = $consumer;
        $this->tranformer = $tranformer;
    }

    public function getMovieById(string $id): Movie
    {
        return $this->getMovie(OmdbApiConsumer::MODE_ID, $id);
    }

    public function getMovieByTitle(string $title): Movie
    {
        return $this->getMovie(OmdbApiConsumer::MODE_TITLE, $title);
    }

    private function getMovie(string $mode, string $value): Movie
    {
        $data = $this->consumer->fetchMovie($mode, $value);

        if ($movieEntity = $this->repository->findOneBy(['title' => $data['Title']])) {
            return $movieEntity;
        }

        $movie = $this->tranformer->transform($data);
        $this->repository->add($movie, true);

        return $movie;
    }
}