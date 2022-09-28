<?php

namespace App\Consumer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiConsumer
{
    public const MODE_ID = 'i';
    public const MODE_TITLE = 't';

    private HttpClientInterface $omdbClient;

    public function __construct(HttpClientInterface $omdbClient)
    {
        $this->omdbClient = $omdbClient;
    }

    public function fetchMovie(string $mode, string $value): array
    {
        if (!\in_array($mode, [self::MODE_ID, self::MODE_TITLE])) {
            throw new \RuntimeException('Bad fetch mode.');
        }

        $response = $this->omdbClient->request(
            Request::METHOD_GET,
            '',
            ['query' => [$mode => $value]]
        )->toArray();

        if (\array_key_exists('Response', $response) && $response['Response'] === 'False') {
            throw new NotFoundHttpException('Movie not found.');
        }

        return $response;
    }
}