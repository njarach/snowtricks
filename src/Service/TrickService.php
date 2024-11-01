<?php

namespace App\Service;

use App\Entity\Trick;
use App\Service\Manager\TrickManager;

class TrickService
{
    private TrickManager $trickManager;
    public function __construct(TrickManager $trickManager)
    {
        $this->trickManager = $trickManager;
    }

    public function getPaginatedTricks(int $page = 1, int $limit = 6): array
    {
        return $this->trickManager->findPaginated($page,$limit);
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
}