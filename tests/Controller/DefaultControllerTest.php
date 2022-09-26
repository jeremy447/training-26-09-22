<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideUriAndStatusCode
     * @group smoke
     */
    public function testPublicUrl(string $uri, int $statusCode): void
    {
        $client = static::createClient();
        $client->request('GET', $uri);

        $this->assertResponseStatusCodeSame($statusCode);
    }

    public function provideUriAndStatusCode()
    {
        return [
            'home' => ['/', 200],
            'hello' => ['/hello', 200],
            'contact' => ['/contact', 200],
            'book' => ['/book', 301],
            'book_details' => ['/book/1', 200],
            'toto' => ['/toto', 404],
        ];
    }
}
