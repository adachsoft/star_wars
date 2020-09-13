<?php

declare(strict_types = 1);

namespace App\Pagination\DataPaginationProvider;

use App\Pagination\Model\DataPaginationProviderInterface;
use App\Repository\Model\CharactersRepositoryInterface;

class CharactersDataPaginationProvider implements DataPaginationProviderInterface
{
    /**
     * @var CharactersRepositoryInterface
     */
    private $charactersRepository;

    public function __construct(CharactersRepositoryInterface $charactersRepository)
    {
        $this->charactersRepository = $charactersRepository;
    }

    public function getCount(): int
    {
        return $this->charactersRepository->countAll();
    }

    public function getData(int $offset, int $limit): iterable
    {
        return $this->charactersRepository->findWithOffsetAndLimit($offset, $limit);
    }
}
