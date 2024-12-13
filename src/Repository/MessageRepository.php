<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function findByTrick(Trick $trick): \Doctrine\ORM\Query
    {
        return $this->createQueryBuilder('m')->where('m.trick = :trick')->setParameter('trick',$trick)->getQuery();
    }
}
