<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserPolicy extends AbstractPolicy
{
    protected $permissionPrefix = 'user';

    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, object $model): bool
    {
        return $user->is($model) || parent::update($user, $model);
    }

    public function updating(User $user, User $record, array $attributes, array $relationships): bool
    {
        return !array_diff(array_keys($attributes), []) &&
            !array_diff(array_keys($relationships), ['active-currency-account', 'games']);
    }

    /**
     * Determine whether the user can view some model.
     */
    public function read(User $user, Model $model): bool
    {
        return $user->is($model) || parent::read($user, $model);
    }

    public function modifyRelationship(User $user, Model $model, string $field): bool
    {
        return $user->is($model) && in_array($field, ['games','events']);
    }

    public function readRelationship(User $user, Model $model, string $field): bool
    {
        return $user->is($model) || parent::readRelationship($user, $model, $field);
    }
}
