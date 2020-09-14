<?php

declare(strict_types = 1);

namespace Tests\Functional\Controller;

use App\Entity\Characters;
use App\Repository\CharactersRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helper\AssertTrait;

class CharactersControllerWithExceptionsTest extends WebTestCase
{
    use AssertTrait;

    public function testOneWhenRecordNotFound(): void
    {
        $id = 0;

        $client = static::createClient();
        $client->request('GET', "/characters/one/{$id}");

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('status', $result);
        $this->assertSame('Record not found', $result['status']);
    }

    public function testUpdateWhenRecordNotFound(): void
    {
        $id = 0;

        $client = static::createClient();
        $client->request('PUT', "/characters/update/{$id}");

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('status', $result);
        $this->assertSame('Record not found', $result['status']);
    }

    public function testDeleteWhenRecordNotFound(): void
    {
        $id = 0;

        $client = static::createClient();
        $client->request('DELETE', "/characters/delete/{$id}");

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('status', $result);
        $this->assertSame('Record not found', $result['status']);
    }

    public function testAddWrongData(): void
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        /**
         * @var CharactersRepository $charactersRepository
         */
        $charactersRepository = $em->getRepository(Characters::class);
        $numberOfCharacters = $charactersRepository->countAll();

        $data = [
            'name' => 'test',
            'planet' => 'nonexistent planet',
        ];
        
        $client->request('POST', '/characters/add/', ['data' => $data]);

        $this->assertJsonResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame($numberOfCharacters, $charactersRepository->countAll());
        $this->assertArrayHasKey('status', $result);
    }
}
