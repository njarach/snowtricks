<?php

namespace App\Service;

use App\Entity\Trick;
use App\Service\Manager\TrickManager;
use App\Service\Paginator\PaginatorService;

class TrickService
{
    private TrickManager $trickManager;
    public function __construct(TrickManager $trickManager)
    {
        $this->trickManager = $trickManager;
    }

    public function getPaginatedTricks(int $page = 1, int $limit = 5): array
    {
        $query = $this->trickManager->getAllTricks();
        $paginator = new PaginatorService();
        return $paginator->paginate($query,$page,$limit);
    }

    /**
     * @param Trick $trick
     * @return void
     */
    public function persistTrick(Trick $trick):void
    {
        $this->trickManager->persistTrick($trick);
    }

    /**
     * @param Trick $trick
     * @return void
     */
    public function removeTrick(Trick $trick): void
    {
        $this->trickManager->removeTrick($trick);
    }

    public function getLatestTricks(): array
    {
        return $this->trickManager->getLatestTricks();
    }
}