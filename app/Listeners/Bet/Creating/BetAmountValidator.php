<?php

namespace App\Listeners\Bet\Creating;

use App\Events\Bet\BetCreating;
use App\Exceptions\MoneyService\InsufficientMoneyException;

class BetAmountValidator
{
    /**
     * @throws InsufficientMoneyException
     */
    public function handle(BetCreating $creating): void
    {
        $bet = $creating->bet;

        if ($bet->amount > $bet->currencyAccount->amount) {
            throw new InsufficientMoneyException();
        }
    }
}
