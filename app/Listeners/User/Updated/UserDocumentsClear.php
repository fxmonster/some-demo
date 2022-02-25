<?php

namespace App\Listeners\User\Updated;

use App\Events\User\UserUpdated;
use App\Models\User;

final class UserDocumentsClear
{
    public function handle(UserUpdated $userUpdated): void
    {
        if ($userUpdated->user->status !== User::STATUS_BLOCKED &&
            $userUpdated->user->isDirty('status') &&
            (int)$userUpdated->user->getOriginal('status') === User::STATUS_VERIFIED) {
            $userUpdated->user->documents()->update(['visible' => 0]);
        }
    }
}
