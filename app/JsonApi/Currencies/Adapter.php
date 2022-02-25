<?php

namespace App\JsonApi\Currencies;

use App\Models\Currency;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Adapter extends AbstractAdapter
{
    protected $defaultSort = 'code';

    protected $attributes = [
        'minorUnits' => 'minor_units',
    ];

    public function __construct(Currency $currency, StandardStrategy $paging)
    {
        parent::__construct($currency, $paging);
    }

    protected function filter($query, Collection $filters): void
    {
        $filters->put('enabled', 1);

        if ($filters->has('availableForCurrencyAccountCreation')) {
            if (Auth::user()) {
                $query->availableForCurrencyAccountCreation(Auth::user());
            }
            $filters->forget('availableForCurrencyAccountCreation');
        }

        $this->filterWithScopes($query, $filters);
    }
}
