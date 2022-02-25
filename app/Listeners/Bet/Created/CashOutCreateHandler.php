<?php

namespace App\Listeners\Bet\Created;

use App\Events\Bet\BetCreated;
use App\Models\CashOut;

final class CashOutCreateHandler
{
    public function handle(BetCreated $betCreated): void
    {
        // some code
    }
}
