<?php

namespace App\Observers;

use App\Helpers\ImageHelper;
use App\Models\Club;
use Spatie\ResponseCache\Facades\ResponseCache;

final class ClubObserver
{
    public function created(Club $club): void
    {
        $club::flushQueryCacheWithTag($club::MODEL_NAME);
        ResponseCache::clear([$club::MODEL_NAME]);
    }

    public function updated(Club $club): void
    {
        $club::flushQueryCacheWithTag($club::MODEL_NAME);
        ResponseCache::clear([$club::MODEL_NAME]);
    }

    public function deleted(Club $club): void
    {
        ImageHelper::deleteImageFromCDN($club::IMG_PATH, $club->image);
        $club::flushQueryCacheWithTag($club::MODEL_NAME);
        ResponseCache::clear([$club::MODEL_NAME]);
    }
}
