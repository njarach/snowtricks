<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trick>
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    public function findPaginatedTricks(int $page, int $limit)
    {
        $offset = ($page - 1) * $limit;
        $query = $this->createQueryBuilder('t')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        return $query->getQuery()->getResult();
    }

    public function getLatestTricks(): array
    {
        $query = $this->createQueryBuilder('t')
            ->setMaxResults(15)
            ->orderBy('id desc');
        return $query->getQuery()->getResult();
    }
}
