<?php

namespace App\Listeners\Bet\Created;

use App\Events\Bet\BetCreated;
use App\Jobs\Affiliate\BetCreatedAsyncJob;
use Illuminate\Support\Facades\DB;

final class BetCreatedAffiliateNotification
{
    public function handle(BetCreated $betCreated): void
    {
        BetCreatedAsyncJob::dispatch($betCreated->bet)->delay(now()->addSeconds(60));
    }
}
