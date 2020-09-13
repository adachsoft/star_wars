<?php

namespace App\Response\Factory;

use App\Response\Factory\Model\ResponseFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerInterface as JSMSerializerInterface;

class JsonResponseFactory implements ResponseFactoryInterface
{
    /**
     * @var JSMSerializerInterface
     */
    private $serializer;

    public function __construct(JSMSerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param mixed $data
     * @param int   $statusCode
     * @param array $headers
     * @return      Response
     */
    public function create($data, int $statusCode = Response::HTTP_OK, $headers = []): Response
    {
        return new JsonResponse(
            //json_encode($data),
            $this->serializer->serialize($data, 'json'),
            $statusCode,
            $headers,
            true
        );
    }
}
