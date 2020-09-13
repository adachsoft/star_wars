<?php

namespace App\Repository\Model;

use App\Entity\Characters;

interface CharactersRepositoryInterface
{
    public function countAll(): int;

    /**
     * @param integer $offset
     * @param integer $limit
     * @return iterable|Characters[]
     */
    public function findWithOffsetAndLimit(int $offset, int $limit): iterable;
}
