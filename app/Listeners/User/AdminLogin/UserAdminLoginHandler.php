<?php

namespace App\Listeners\User;

use App\Events\User\UserAdminLogin;

final class UserAdminLoginHandler
{
    public function handle(UserAdminLogin $login): void
    {
        $user = $login->user;

        activity('login')
            ->performedOn($user)
            ->causedBy($user)
            ->log('Login in to backpack admin panel');
    }
}
