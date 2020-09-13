<?php

namespace App\Pagination\Model;

interface DataPaginationProviderInterface
{
    public function getCount(): int;

    public function getData(): iterable;
}
