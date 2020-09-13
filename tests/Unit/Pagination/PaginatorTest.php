<?php

namespace Tests\Unit\App\Pagination;

use App\Pagination\Exception\PaginatorException;
use App\Pagination\Model\DataPaginationProviderInterface;
use App\Pagination\Model\PaginatorInterface;
use App\Pagination\Paginator;
use PHPUnit\Framework\TestCase;

class PaginatorTest extends TestCase
{
    public function testInstance(): void
    {
        $dataPaginationProvider = $this->createMock(DataPaginationProviderInterface::class);

        $paginator = new Paginator($dataPaginationProvider);
        $this->assertInstanceOf(PaginatorInterface::class, $paginator);
    }

    public function testCurrentPage(): void
    {
        $dataPaginationProvider = $this->createMock(DataPaginationProviderInterface::class);
        $dataPaginationProvider->expects($this->once())->method('getCount')->willReturn(720);

        $paginator = new Paginator($dataPaginationProvider);
        $this->assertSame(1, $paginator->getCurrentPage());
        $paginator->setCurrentPage(10);
        $this->assertSame(10, $paginator->getCurrentPage());
    }

    public function testTotalItems(): void
    {
        $dataPaginationProvider = $this->createMock(DataPaginationProviderInterface::class);
        $dataPaginationProvider->expects($this->once())->method('getCount')->willReturn(72);

        $paginator = new Paginator($dataPaginationProvider);
        $this->assertSame(72, $paginator->getTotalItems());
    }
    
    /**
     * @dataProvider dataData
     */
    public function testData(int $expectedOffset, int $limit, int $currentPage): void
    {
        $data = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $count = count($data);

        $dataPaginationProvider = $this->createMock(DataPaginationProviderInterface::class);
        $dataPaginationProvider->method('getCount')->willReturn($count);
        $dataPaginationProvider->expects($this->once())->method('getData')->willReturn($data)->with($expectedOffset, $limit);

        $paginator = new Paginator($dataPaginationProvider);
        $paginator->setNumberOfItemsPerPage($limit);
        $paginator->setCurrentPage($currentPage);
        $data = $paginator->getData();
        $this->assertIsIterable($data);
        $this->assertCount($count, $data);
        $this->assertSame($limit, $paginator->getNumberOfItemsPerPage());
    }

    /**
     * @dataProvider dataShouldThrowExceptionWhenSetCurrentPage
     */
    public function testShouldThrowExceptionWhenSetCurrentPage(int $currentPage, int $count): void
    {
        $this->expectException(PaginatorException::class);

        $dataPaginationProvider = $this->createMock(DataPaginationProviderInterface::class);
        $dataPaginationProvider->method('getCount')->willReturn($count);

        $paginator = new Paginator($dataPaginationProvider);
        $paginator->setCurrentPage($currentPage);
    }

    /**
     * @dataProvider dataShouldThrowExceptionWhenSetNumberOfItemsPerPageLessThanOne
     */
    public function testShouldThrowExceptionWhenSetNumberOfItemsPerPageLessThanOne(int $numberOfItemsPerPage): void
    {
        $this->expectException(PaginatorException::class);

        $dataPaginationProvider = $this->createMock(DataPaginationProviderInterface::class);
        $paginator = new Paginator($dataPaginationProvider);

        $paginator->setNumberOfItemsPerPage($numberOfItemsPerPage);
    }

    /**
     * @dataProvider dataNumberOfPages
     */
    public function testNumberOfPages(int $expectedNumberOfPages, int $count, int $numberOfItemsPerPage): void
    {
        $dataPaginationProvider = $this->createMock(DataPaginationProviderInterface::class);
        $dataPaginationProvider->expects($this->once())->method('getCount')->willReturn($count);

        $paginator = new Paginator($dataPaginationProvider);
        $paginator->setNumberOfItemsPerPage($numberOfItemsPerPage);
        $this->assertSame($expectedNumberOfPages, $paginator->getNumberOfPages());
    }

    public function dataNumberOfPages(): array
    {
        return [
            [12, 72, 6],
            [12, 71, 6],
            [12, 67, 6],
            [11, 66, 6],
            [1, 0, 6],
            [1, 3, 6],
            [6, 72, 12],
        ];
    }

    public function dataShouldThrowExceptionWhenSetNumberOfItemsPerPageLessThanOne(): array
    {
        return [
            [-1],
            [0],
            [-100],
        ];
    }

    public function dataShouldThrowExceptionWhenSetCurrentPage(): array
    {
        return [
            [-1, 72],
            [0, 72],
            [-100, 72],
            [2160, 72],
        ];
    }

    public function dataData(): array
    {
        return [
            ['expectedOffset' => 4, 'limit' => 2, 'currentPage' => 3],
            ['expectedOffset' => 2, 'limit' => 2, 'currentPage' => 2],
            ['expectedOffset' => 0, 'limit' => 2, 'currentPage' => 1],
        ];
    }
}
