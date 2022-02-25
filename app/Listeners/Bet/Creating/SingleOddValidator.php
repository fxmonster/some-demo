<?php

namespace App\Listeners\Bet\Creating;

use App\Events\Bet\BetCreatingSingle;
use App\Exceptions\Odd\OddExpiredException;
use App\Exceptions\Odd\OddNotFoundException;

class SingleOddValidator
{
    /**
     * @throws OddNotFoundException|OddExpiredException
     */
    public function handle(BetCreatingSingle $creating): void
    {
        $singles = $creating->order->singles;

        foreach ($singles as $single) {
            if (!$single->prebet->odd) {
                throw new OddNotFoundException();
            }
        }
    }
}
