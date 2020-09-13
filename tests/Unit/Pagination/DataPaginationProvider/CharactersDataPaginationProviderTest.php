<?php

namespace Tests\Unit\App\Pagination\DataPaginationProvider;

use App\Pagination\DataPaginationProvider\CharactersDataPaginationProvider;
use App\Pagination\Model\DataPaginationProviderInterface;
use App\Repository\Model\CharactersRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CharactersDataPaginationProviderTest extends TestCase
{
    public function testInstance(): void
    {
        $charactersRepository = $this->createMock(CharactersRepositoryInterface::class);

        $dataPaginationProvider = new CharactersDataPaginationProvider($charactersRepository);
        $this->assertInstanceOf(DataPaginationProviderInterface::class, $dataPaginationProvider);
    }

    public function testCount(): void
    {
        $charactersRepository = $this->createMock(CharactersRepositoryInterface::class);
        $charactersRepository->expects($this->once())->method('countAll')->willReturn(12);

        $dataPaginationProvider = new CharactersDataPaginationProvider($charactersRepository);
        $this->assertSame(12, $dataPaginationProvider->getCount());
    }

    public function testData(): void
    {
        $charactersRepository = $this->createMock(CharactersRepositoryInterface::class);
        $charactersRepository->expects($this->once())->method('findWithOffsetAndLimit')->willReturn([])->with(5, 100);

        $dataPaginationProvider = new CharactersDataPaginationProvider($charactersRepository);
        $this->assertIsIterable($dataPaginationProvider->getData(5, 100));
    }
}
