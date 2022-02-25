<?php

namespace App\JsonApi\Clubs;

use App\Models\Club;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Support\Collection;

class Adapter extends AbstractAdapter
{
    public function __construct(Club $clubs, StandardStrategy $paging)
    {
        parent::__construct($clubs, $paging);
    }

    protected function filter($query, Collection $filters): void
    {
        $this->filterWithScopes($query, $filters);
    }
}
