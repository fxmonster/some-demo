<?php

namespace App\Observers;

use App\Models\Country;
use Spatie\ResponseCache\Facades\ResponseCache;

final class CountryObserver
{
    public function updated(Country $country): void
    {
        $country::flushQueryCacheWithTag($country::MODEL_NAME);
        ResponseCache::clear([$country::MODEL_NAME]);
    }
}
