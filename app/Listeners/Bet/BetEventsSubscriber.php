<?php

namespace App\Listeners\Bet;

use App\Events\Bet\BetCreated;
use App\Events\Bet\BetCreatingExpress;
use App\Events\Bet\BetCreatingSingle;
use App\Events\Bet\BetCreatingSystem;
use App\Events\Bet\BetUpdated;
use App\Listeners\AbstractEventsSubscriber;
use App\Listeners\Bet\Created\BetCreatedAffiliateNotification;
use App\Listeners\Bet\Created\CashOutCreateHandler;
use App\Listeners\Bet\Created\EventReserveUpdater;
use App\Listeners\Bet\Created\ScoreCheckAfterHandler;
use App\Listeners\Bet\Creating\MaxOddLimitExpressValidator;
use App\Listeners\Bet\Creating\MaxOddLimitSingleValidator;
use App\Listeners\Bet\Creating\MaxOddLimitSystemValidator;
use App\Listeners\Bet\Creating\SingleOddValidator;
use App\Listeners\Bet\Updated\BetUpdatedAffiliateNotification;
use App\Listeners\Bet\Created\PlayedAccumulatorUpdater;

class BetEventsSubscriber extends AbstractEventsSubscriber
{
    protected array $listen = [
        BetCreated::class => [
            CashOutCreateHandler::class,
            PlayedAccumulatorUpdater::class,
            BetCreatedAffiliateNotification::class,
            EventReserveUpdater::class,
            //  UserFirstBetHandler::class,
            ScoreCheckAfterHandler::class,
        ],

        BetCreatingSingle::class => [
            SingleOddValidator::class,
            MaxOddLimitSingleValidator::class,
        ],

        BetCreatingExpress::class => [
            MaxOddLimitExpressValidator::class,
        ],

        BetCreatingSystem::class => [
            MaxOddLimitSystemValidator::class,
        ],

        BetUpdated::class => [
            BetUpdatedAffiliateNotification::class,
        ],
    ];

}
