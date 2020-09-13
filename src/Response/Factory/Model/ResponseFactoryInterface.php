<?php

namespace App\Response\Factory\Model;

use Symfony\Component\HttpFoundation\Response;

interface ResponseFactoryInterface
{
    /**
     * @param mixed $data
     * @param int   $statusCode
     * @param array $headers
     * @return      Response
     */
    public function create($data, int $statusCode = Response::HTTP_OK, $headers = []): Response;
}
