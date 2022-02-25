<?php

namespace App\Jobs\Affiliate;

use App\Jobs\AbstractAsyncJob;
use App\Models\Payment\Payment;
use App\Services\AffiliateService;

final class PaymentUpdatedAsyncJob extends AbstractAsyncJob
{
    protected const QUEUE_NAME_PREFIX = 'affiliate';

    public int $tries = 3;

    public function __construct(private Payment $payment)
    {
        $this->onQueue(self::getQueueName());
    }

    public function handle(AffiliateService $service): void
    {
        if ($this->payment->status === Payment::STATUS_SUCCESS && $this->payment->isDirectionIn()) {
            $service->deposit($this->payment);
        }

        if ($this->payment->status === Payment::STATUS_SUCCESS && $this->payment->isDirectionOut()) {
            $service->withdrawal($this->payment);
        }
    }
}
