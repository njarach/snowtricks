<?php

namespace App\Service\Manager;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;

class TrickManager
{
    private TrickRepository $trickRepository;
    private EntityManagerInterface $entityManager;
    public function __construct(TrickRepository$trickRepository, EntityManagerInterface $entityManager)
    {
        $this->trickRepository = $trickRepository;
        $this->entityManager = $entityManager;
    }

    public function findPaginated(int $page, int $limit): array
    {
        return $this->trickRepository->findPaginatedTricks($page,$limit);
    }

    /**
     * @param Trick $trick
     * @return void
     */
    public function persistTrick(Trick $trick): void
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
}