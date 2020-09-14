<?php

declare(strict_types = 1);

namespace Tests\Functional\Controller;

use App\Entity\Characters;
use App\Repository\CharactersRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Helper\AssertTrait;

class CharactersControllerTest extends WebTestCase
{
    use AssertTrait;

    public function testOne(): void
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');

        /**
         * @var Characters $character
         */
        $character = $em->getRepository(Characters::class)->findOneBy(['name' => 'test']);
        $id = $character->getId();

        $client->request('GET', "/characters/one/{$id}");

        $this->assertJsonResponse(Response::HTTP_OK, $client->getResponse());
        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('id', $result);
        $this->assertSame($character->getName(), $result['name']);
        $this->assertSame($id, $result['id']);
    }

    public function testAdd(): int
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        /**
         * @var CharactersRepository $charactersRepository
         */
        $charactersRepository = $em->getRepository(Characters::class);
        $numberOfCharacters = $charactersRepository->countAll();

        $data = [
            'name' => 'Luke Skywalker - test',
            'episodes' => [['name' => 'NEWHOPE']],
            'friends' => [['name' => 'test 1']],
        ];
        
        $client->request('POST', '/characters/add/', ['data' => $data]);

        $this->assertJsonResponse(Response::HTTP_CREATED, $client->getResponse());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame($numberOfCharacters + 1, $charactersRepository->countAll());
        $this->assertArrayHasKey('id', $result);

        return $result['id'];
    }

    /**
     * @depends testAdd
     */
    public function testUpdate(int $id): void
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        /**
         * @var CharactersRepository $charactersRepository
         */
        $charactersRepository = $em->getRepository(Characters::class);
        $numberOfCharacters = $charactersRepository->countAll();

        $data = [
            'name' => 'Luke Skywalker',
            'planet' => 'Planet-X',
            'friends' => [['name' => 'test 2']],
        ];
        
        $client->request('PUT', "/characters/update/{$id}", ['data' => $data]);

        $this->assertJsonResponse(Response::HTTP_OK, $client->getResponse());
        $this->assertSame($numberOfCharacters, $charactersRepository->countAll());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $result);
        $this->assertSame($id, $result['id']);
        $this->assertSame($data['name'], $result['name']);
        $this->assertCount(1, $result['friends']);

        $conn = $em->getConnection();
        $sql = 'SELECT * FROM characters WHERE id = ' . $id;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $character = $stmt->fetch();
        $this->assertSame($data['name'], $character['name']);
    }

    /**
     * @depends testAdd
     */
    public function testDelete(int $id): void
    {
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');
        /**
         * @var CharactersRepository $charactersRepository
         */
        $charactersRepository = $em->getRepository(Characters::class);
        $numberOfCharacters = $charactersRepository->countAll();
        
        $client->request('DELETE', "/characters/delete/{$id}");

        $this->assertJsonResponse(Response::HTTP_OK, $client->getResponse());

        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame($numberOfCharacters -1, $charactersRepository->countAll());
    }
}
