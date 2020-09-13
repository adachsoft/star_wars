<?php

namespace Tests\Unit\App\Response\Factory;

use App\Response\Factory\JsonResponseFactory;
use PHPUnit\Framework\TestCase;
use App\Response\Factory\Model\ResponseFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerInterface as JSMSerializerInterface;

class JsonResponseFactoryTest extends TestCase
{
    public function testInstance(): void
    {
        $serializer = $this->createMock(JSMSerializerInterface::class);

        $responseFactory = new JsonResponseFactory($serializer);
        $this->assertInstanceOf(ResponseFactoryInterface::class, $responseFactory);
    }

    public function testCreate(): void
    {
        $data = ['test'];

        $serializer = $this->createMock(JSMSerializerInterface::class);
        $serializer->expects($this->once())->method('serialize')->with($data, 'json')->willReturn(json_encode($data));

        $responseFactory = new JsonResponseFactory($serializer);
        $result = $responseFactory->create($data);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(json_encode($data), $result->getContent());
        $this->assertEquals('application/json', $result->headers->get('Content-Type'));
    }
}
