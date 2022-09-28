<?php

namespace App\Provider;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Transformer\OmdbMovieTranformer;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieProvider
{
    private MovieRepository $repository;
    private OmdbApiConsumer $consumer;
    private OmdbMovieTranformer $tranformer;
    private ?SymfonyStyle $io = null;

    public function __construct(
        MovieRepository $repository,
        OmdbApiConsumer $consumer,
        OmdbMovieTranformer $tranformer
    ) {
        $this->repository = $repository;
        $this->consumer = $consumer;
        $this->tranformer = $tranformer;
    }

    public function setIo(SymfonyStyle $io): void
    {
        $this->io = $io;
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
        $this->sendOutput('text', 'Searching on OMDb.');
        $data = $this->consumer->fetchMovie($mode, $value);

        if ($movieEntity = $this->repository->findOneBy(['title' => $data['Title']])) {
            $this->sendOutput('note', 'Movie already in database!');
            return $movieEntity;
        }

        $movie = $this->tranformer->transform($data);
        $this->sendOutput('text', 'Adding movie in database.');
        $this->repository->add($movie, true);

        return $movie;
    }

    private function sendOutput(string $type, string $message)
    {
        if ($this->io instanceof SymfonyStyle) {
            $this->io->$type($message);
        }
    }
}