<?php

namespace App\JsonApi\Tags;

use App\Enum\BonusAccountTypesEnum;
use App\Models\Tag;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Support\Collection;

class Adapter extends AbstractAdapter
{
    public function __construct(Tag $tag, StandardStrategy $paging)
    {
        parent::__construct($tag, $paging);
    }

    protected function filter($query, Collection $filters): void
    {
        $query->enabled();
        $query->byNotCompleted();
        $query->showUI();
        $query->byTypes(BonusAccountTypesEnum::MANUAL_ENROLLMENT);

        $this->filterWithScopes($query, $filters);
    }

}
