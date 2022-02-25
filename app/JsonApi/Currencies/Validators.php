<?php

namespace App\JsonApi\Currencies;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    protected $allowedIncludePaths = [];

    protected $allowedSortParameters = [];

    protected $allowedFilteringParameters = [
        'availableForCurrencyAccountCreation',
    ];

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
