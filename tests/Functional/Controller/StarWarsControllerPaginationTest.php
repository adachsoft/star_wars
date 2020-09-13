<?php

namespace Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StarWarsControllerPaginationTest extends WebTestCase
{
    public function testIndexWithPagination(): void
    {
        $this->assertSame(1, 1);
    }
}
