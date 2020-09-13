<?php

declare(strict_types = 1);

namespace App\Pagination\Factory;

use App\Entity\Characters;
use App\Pagination\DataPaginationProvider\CharactersDataPaginationProvider;
use App\Pagination\Model\PaginatorInterface;
use App\Pagination\Paginator;
use App\Repository\Model\CharactersRepositoryInterface;
use RuntimeException;

class PaginatorFactory
{
    /**
     * @var CharactersRepositoryInterface
     */
    private $charactersRepository;

    public function __construct(CharactersRepositoryInterface $charactersRepository)
    {
        $this->charactersRepository = $charactersRepository;
    }

    public function create(string $className): PaginatorInterface
    {
        if (Characters::class !== $className) {
            throw new RuntimeException('Unsupported type');
        }

        $dataPaginationProvider = new CharactersDataPaginationProvider(
            $this->charactersRepository
        );

        return new Paginator($dataPaginationProvider);
    }
}
