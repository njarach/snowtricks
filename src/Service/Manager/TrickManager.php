<?php

namespace App\Service\Manager;

use App\Repository\TrickRepository;

class TrickManager
{
    private TrickRepository $trickRepository;
    public function __construct(TrickRepository$trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }

    public function findPaginated(int $page, int $limit): array
    {
        return $this->trickRepository->findPaginatedTricks($page,$limit);
    }
}