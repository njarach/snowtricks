<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    public function getAllTricks(): Query
    {
        return $this->createQueryBuilder('t')->getQuery();
    }

    public function getLatestTricks(): array
    {
        $query = $this->createQueryBuilder('t')
            ->setMaxResults(15);
        return $query->getQuery()->getResult();
    }
}
