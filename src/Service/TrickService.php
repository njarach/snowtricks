<?php

namespace App\Service;

use App\Service\Manager\TrickManager;

class TrickService
{
    private TrickManager $trickManager;
    public function __construct(TrickManager $trickManager)
    {
        $this->trickManager = $trickManager;
    }

    public function getPaginatedTricksHomepage(int $page = 1, int $limit = 5): array
    {
        return $this->trickManager->findPaginated($page,$limit);
    }

}