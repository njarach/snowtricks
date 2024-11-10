<?php

namespace App\Service\Paginator;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatorService
{
    public function paginate(Query $query, int $page, int $limit):array
    {
        $offset = ($page - 1) * $limit;
        $query->setFirstResult($offset)->setMaxResults($limit);

        $paginator = new Paginator($query);
        $totalItems = count($paginator);

        $totalPages = ceil($totalItems / $limit);

        return [
            'items' => iterator_to_array($paginator),
            'totalItems' => $totalItems,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ];
    }
}