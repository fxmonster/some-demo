<?php

namespace App\Events\User;

use App\Models\User;

final class UserUpdated
{
    public function __construct(public User $user)
    {
    }
}
