<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractPolicy
{
    /** @var string */
    protected $permissionPrefix;

    public function index(User $user): bool
    {
        return $user->checkPermissionTo($this->permissionPrefix . '.index');
    }

    public function read(User $user, Model $model): bool
    {
        return $user->checkPermissionTo($this->permissionPrefix . '.read');
    }

    public function create(User $user): bool
    {
        return $user->checkPermissionTo($this->permissionPrefix . '.create');
    }

    public function update(User $user, object $model): bool
    {
        return $user->checkPermissionTo($this->permissionPrefix . '.update');
    }

    public function delete(User $user, Model $model): bool
    {
        return $user->checkPermissionTo($this->permissionPrefix . '.delete');
    }

    public function modifyRelationship(User $user, Model $model, string $field): bool
    {
        return $user->checkPermissionTo($field . '.update');
    }

    public function readRelationship(User $user, Model $model, string $field): bool
    {
        return $user->checkPermissionTo($field . '.read');
    }
}
