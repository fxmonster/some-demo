<?php

namespace App\Listeners\User\Created;

use App\Events\User\UserCreated;
use App\Jobs\Affiliate\UserCreatedAsyncJob;
use Illuminate\Support\Facades\DB;

final class UserCreatedAffiliateNotification
{
    public function handle(UserCreated $event): void
    {
        UserCreatedAsyncJob::dispatch($event->user)->delay(now()->addSeconds(60));
    }
}
