<?php

namespace App\Repository;

use App\Entity\Episodes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Episodes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Episodes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Episodes[]    findAll()
 * @method Episodes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EpisodesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Episodes::class);
    }
}
