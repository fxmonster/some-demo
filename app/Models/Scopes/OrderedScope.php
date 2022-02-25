<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderedScope implements Scope
{
    public function apply(Builder $builder, Model $model, string $direction = 'asc'): void
    {
        $builder->orderBy('order', $direction);
    }
}
