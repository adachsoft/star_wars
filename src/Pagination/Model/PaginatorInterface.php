<?php

namespace App\Pagination\Model;

interface PaginatorInterface
{
    public function getCurrentPage(): int;

    public function setCurrentPage(int $page): void;

    public function getTotalItems(): int;

    public function getNumberOfPages(): int;

    public function setNumberOfItemsPerPage(int $numberOfItemsPerPage): void;

    public function getData(): iterable;
}
