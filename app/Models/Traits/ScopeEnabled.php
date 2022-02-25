<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ScopeEnabled
{
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('enabled', '=', 1);
    }
}
