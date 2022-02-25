<?php

namespace App\Listeners;

use App\Listeners\AccessToken\Created\BetslipCreator;
use App\Listeners\AccessToken\Created\BonusAccountCreator;
use App\Listeners\AccessToken\Created\PlayedAccumulatorCreator;
use App\Listeners\AccessToken\Created\UserSettingsCreator;
use Laravel\Passport\Events\AccessTokenCreated;

abstract class AbstractEventsSubscriber
{
    // property need to override at child class
    protected array $listen = [];

    public function subscribe($events): void
    {
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }
}
