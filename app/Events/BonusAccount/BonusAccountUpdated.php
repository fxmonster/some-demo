<?php

namespace App\Events\BonusAccount;

use App\Models\BonusAccount;

final class BonusAccountUpdated
{
    public function __construct(public BonusAccount $bonusAccount)
    {
    }
}
