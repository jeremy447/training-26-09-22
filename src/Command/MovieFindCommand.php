<?php

namespace App\Command;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Provider\MovieProvider;
use App\Repository\MovieRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MovieFindCommand extends Command
{
    private const MODES = [
        't' => 'title',
        'i' => 'id',
        'title' => 'title',
        'id' => 'id',
    ];
    private MovieProvider $provider;
    private MovieRepository $repository;

    public function __construct(MovieProvider $provider, MovieRepository $repository)
    {
        parent::__construct();
        $this->provider = $provider;
        $this->repository = $repository;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:movie:find')
            ->setDescription('Find a movie by title or Imdb ID')
            ->addArgument('value', InputArgument::OPTIONAL, 'The title or if of the movie you want.')
            ->addArgument('mode', InputArgument::OPTIONAL, 'The type of value you provided (title or id)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->provider->setIo($io);

        if (null === $value = $input->getArgument('value')) {
            $value = $io->ask("What is the title or Imdb ID of the movie you wish to import?");
        }

        $mode = $input->getArgument('mode');
        if (null === $mode || !\array_key_exists($mode, self::MODES)) {
            $mode = $io->choice(sprintf("Is \"%s\" a title or an Imdb ID?", $value), ['t' => 'title', 'i'=> 'id']);
        }
        $mode = self::MODES[$mode];

        $io->title('Your search :');
        $io->text(sprintf("Searching for a movie with %s \"%s\" on OMDb API.", $mode, $value));
        if ($mode === 'id' && $movie = $this->repository->findOneBy(['imdbId' => $value])) {
            $io->note('Movie found in database!');
            $this->displayMovieTable($movie, $io);

            return Command::SUCCESS;
        }

        try {
            $method = 'getMovieBy' . ucfirst($mode);
            $movie = $this->provider->$method($value);
        } catch (NotFoundHttpException $e) {
            $io->error('Movie not found!');

            return Command::FAILURE;
        }

        $io->section('Movie Found!');
        $this->displayMovieTable($movie, $io);

        return Command::SUCCESS;
    }

    private function displayMovieTable(Movie $movie, SymfonyStyle $io)
    {
        $io->table(['Id', 'IMDb ID', 'Title', 'Rated'], [
            [$movie->getId(), $movie->getImdbId(), $movie->getTitle(), $movie->getRated()],
        ]);
    }
}
