<?php

namespace App\Jobs\Affiliate;

use App\Jobs\AbstractAsyncJob;
use App\Models\Bet;
use App\Services\AffiliateService;

final class BetUpdatedAsyncJob extends AbstractAsyncJob
{
    public int $tries = 3;

    protected const QUEUE_NAME_PREFIX = 'affiliate';

    public function __construct(private Bet $bet)
    {
        $this->onQueue(self::getQueueName());
    }

    public function handle(AffiliateService $service): void
    {
        if ($this->bet->status === Bet::STATUS_RETURN ||
            $this->bet->status === Bet::STATUS_REJECTED) {
            $service->deleteBet($this->bet);
        } else {
            $service->betResult($this->bet);
        }
    }
}
