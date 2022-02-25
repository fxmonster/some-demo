<?php

namespace App\Listeners\User\Updated;

use App\Events\User\UserUpdated;
use App\Jobs\SendUserDocumentsAsyncJob;
use App\Models\User;
use Carbon\Carbon;

final class UserDocumentsUploaded
{
    public function handle(UserUpdated $userUpdated): void
    {
        if ($userUpdated->user->status !== User::STATUS_BLOCKED &&
            $userUpdated->user->isDirty('status') &&
            ((int)$userUpdated->user->getOriginal('status') === User::STATUS_ACTIVE &&
                $userUpdated->user->status === User::STATUS_PROCESSING
            )) {
            SendUserDocumentsAsyncJob::dispatch($userUpdated->user)->delay(Carbon::now()->addMinute());
        }
    }
}
