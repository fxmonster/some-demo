<?php

namespace App\Jobs\Affiliate;

use App\Jobs\AbstractAsyncJob;
use App\Models\Casino\GameTransaction;
use App\Services\AffiliateService;

final class GameTransactionCreatedAsyncJob extends AbstractAsyncJob
{
    public int $tries = 3;

    protected const QUEUE_NAME_PREFIX = 'affiliate';

    public function __construct(private GameTransaction $transaction)
    {
        $this->onQueue(self::getQueueName());
    }

    public function handle(AffiliateService $service): void
    {
        if ($this->transaction->type === GameTransaction::TYPE_BET) {
            $service->casinoBet($this->transaction);
        }

        if ($this->transaction->type === GameTransaction::TYPE_WIN ||
            $this->transaction->type === GameTransaction::TYPE_REFUND) {
            $service->casinoWin($this->transaction);
        }
    }
}
