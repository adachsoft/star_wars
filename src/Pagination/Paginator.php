<?php

namespace App\Pagination;

use App\Pagination\Exception\PaginatorException;
use App\Pagination\Model\DataPaginationProviderInterface;
use App\Pagination\Model\PaginatorInterface;

class Paginator implements PaginatorInterface
{
    /**
     * @var int
     */
    private $currentPage = 1;

    /**
     * @var int
     */
    private $numberOfItemsPerPage = 6;

    /**
     * @var DataPaginationProviderInterface
     */
    private $dataPaginationProvider;

    public function __construct(DataPaginationProviderInterface $dataPaginationProvider)
    {
        $this->dataPaginationProvider = $dataPaginationProvider;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $page): void
    {
        if ($page < 1) {
            throw new PaginatorException('The current page cannot be less than 1.');
        }

        if ($page > $this->getNumberOfPages()) {
            throw new PaginatorException('The current page cannot be greater than the number of pages.');
        }

        $this->currentPage = $page;
    }

    public function getTotalItems(): int
    {
        return $this->dataPaginationProvider->getCount();
    }

    public function getNumberOfPages(): int
    {
        $totalItems = $this->getTotalItems();
        if (0 === $totalItems) {
            return 1;
        }

        return (int)ceil((float)$totalItems / (float)$this->numberOfItemsPerPage);
    }

    public function setNumberOfItemsPerPage(int $numberOfItemsPerPage): void
    {
        if ($numberOfItemsPerPage < 1) {
            throw new PaginatorException('The number of items on the page cannot be less than 1.');
        }

        $this->numberOfItemsPerPage = $numberOfItemsPerPage;
    }

    public function getData(): iterable
    {
        return $this->dataPaginationProvider->getData();
    }
}
