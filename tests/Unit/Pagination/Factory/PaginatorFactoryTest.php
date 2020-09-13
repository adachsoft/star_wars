<?php

declare(strict_types = 1);

namespace Tests\Unit\App\Pagination\Factory;

use App\Entity\Characters;
use App\Entity\Episodes;
use App\Pagination\Factory\PaginatorFactory;
use App\Pagination\Model\PaginatorInterface;
use App\Repository\Model\CharactersRepositoryInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class PaginatorFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $charactersRepository = $this->createMock(CharactersRepositoryInterface::class);

        $factory = new PaginatorFactory($charactersRepository);
        $result = $factory->create(Characters::class);
        $this->assertInstanceOf(PaginatorInterface::class, $result);
    }

    public function testShouldThrowExceptionWhenCreatingWithAnUnsupportedType(): void
    {
        $this->expectException(RuntimeException::class);
        $charactersRepository = $this->createMock(CharactersRepositoryInterface::class);

        $factory = new PaginatorFactory($charactersRepository);
        $factory->create(Episodes::class);
    }
}
