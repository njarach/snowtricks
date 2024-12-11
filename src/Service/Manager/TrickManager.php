<?php

namespace App\Service\Manager;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

class TrickManager
{
    private TrickRepository $trickRepository;
    private EntityManagerInterface $entityManager;
    public function __construct(TrickRepository$trickRepository, EntityManagerInterface $entityManager)
    {
        $this->trickRepository = $trickRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllTricks(): Query
    {
         return $this->trickRepository->getAllTricks();
    }

    /**
     * @param Trick $trick
     * @return void
     */
    public function persistAndFlushTrick(Trick $trick): void
    {
        $this->entityManager->persist($trick);
        $this->entityManager->flush();
    }

    /**
     * @param Trick $trick
     * @return void
     */
    public function removeTrick(Trick $trick): void
    {
        $this->entityManager->remove($trick);
        $this->entityManager->flush();
    }

    public function getLatestTricks(): array
    {
        return $this->trickRepository->getLatestTricks();
    }
}