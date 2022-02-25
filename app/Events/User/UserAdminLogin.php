<?php

namespace App\Events\User;

use App\Models\User;

final class UserAdminLogin
{
    public function __construct(public User $user)
    {
    }
}
