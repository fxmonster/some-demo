<?php

namespace App\Observers;

use App\Models\Casino\Game;
use App\Models\Casino\GameCategory;
use App\Models\Casino\GameProvider;
use App\Models\Casino\GameSlider;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\ResponseCache\Facades\ResponseCache;

class UserObserver
{
    public function creating(User $user): void
    {
        $user->uuid = (string)Str::uuid();
        $user->status = User::STATUS_INACTIVE;
    }

    public function updated(User $user): void
    {
        GameSlider::flushQueryCacheWithTag(GameSlider::MODEL_NAME);
        GameProvider::flushQueryCacheWithTag(GameProvider::MODEL_NAME);
        GameCategory::flushQueryCacheWithTag(GameCategory::MODEL_NAME);

        ResponseCache::clear([GameSlider::MODEL_NAME]);
        ResponseCache::clear([GameProvider::MODEL_NAME]);
        ResponseCache::clear([GameCategory::MODEL_NAME]);
        ResponseCache::clear([Game::MODEL_NAME]);
    }

}
