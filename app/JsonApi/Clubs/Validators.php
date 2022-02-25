<?php

namespace App\JsonApi\Clubs;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    protected $allowedIncludePaths = [];

    protected $allowedSortParameters = [];

    protected $allowedFilteringParameters = [];

    protected function rules($record, array $data): array
    {
        return [
            //
        ];
    }

    protected function queryRules(): array
    {
        return [
            //
        ];
    }
}
