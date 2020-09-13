<?php

declare(strict_types = 1);

namespace App\Pagination\Model;

interface DataPaginationProviderInterface
{
    public function getCount(): int;

    public function getData(int $offset, int $limit): iterable;
}
