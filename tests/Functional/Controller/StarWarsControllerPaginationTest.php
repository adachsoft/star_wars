<?php

namespace Tests\Functional\Controller;

use App\Entity\Characters;
use App\Repository\CharactersRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helper\AssertTrait;

class StarWarsControllerPaginationTest extends WebTestCase
{
    use AssertTrait;

    public function testIndexWithPagination(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

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
        //$this->assertCount(5, $result['characters']);
    }
}
