<?php

namespace Tests\Helper;

use Symfony\Component\HttpFoundation\Response;

trait AssertTrait
{
    private function assertJsonResponse(int $expectedStatusCode, Response $response): void
    {
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertSame($response->headers->get('Content-Type'), 'application/json');
    }
}
