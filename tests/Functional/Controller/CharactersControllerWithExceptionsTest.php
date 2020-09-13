<?php

declare(strict_types = 1);

namespace Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CharactersControllerWithExceptionsTest extends WebTestCase
{
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
}
