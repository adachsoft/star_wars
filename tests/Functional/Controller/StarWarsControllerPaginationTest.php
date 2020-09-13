<?php

declare(strict_types = 1);

namespace Tests\Functional\Controller;

use App\Entity\Characters;
use App\Repository\CharactersRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helper\AssertTrait;

class StarWarsControllerPaginationTest extends WebTestCase
{
    use AssertTrait;

    /**
     * @dataProvider dataIndexWithPagination
     */
    public function testIndexWithPagination(int $expectedCurrentPage, string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        /**
         * @var CharactersRepository $charactersRepository
         */
        $charactersRepository = $em->getRepository(Characters::class);
        $numberOfCharacters = $charactersRepository->countAll();

        $this->assertJsonResponse(Response::HTTP_OK, $client->getResponse());
        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertSame($numberOfCharacters, $result['pagination']['total']);
        $this->assertSame($expectedCurrentPage, $result['pagination']['current_page']);
        $this->assertCount(5, $result['characters']);
    }

    public function testShouldReturnCodeHTTPNotAcceptable(): void
    {
        $client = static::createClient();
        $client->request('GET', '/-1');

        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        /**
         * @var CharactersRepository $charactersRepository
         */
        $charactersRepository = $em->getRepository(Characters::class);
        $numberOfCharacters = $charactersRepository->countAll();

        $this->assertJsonResponse(Response::HTTP_NOT_ACCEPTABLE, $client->getResponse());
        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
    }

    public function dataIndexWithPagination(): array
    {
        return [
            [1, '/'],
            [1, '/1'],
            [4, '/4'],
        ];
    }
}
