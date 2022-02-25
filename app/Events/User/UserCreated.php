<?php

namespace App\Events\User;

use App\Models\User;

final class UserCreated
{
    public function __construct(public User $user, public $bonusType = null)
    {
    }
}
