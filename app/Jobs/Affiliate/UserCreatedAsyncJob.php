<?php

namespace App\Jobs\Affiliate;

use App\Jobs\AbstractAsyncJob;
use App\Models\User;
use App\Services\AffiliateService;

final class UserCreatedAsyncJob extends AbstractAsyncJob
{
    protected const QUEUE_NAME_PREFIX = 'affiliate';

    public int $tries = 3;

    public function __construct(private User $user)
    {
        $this->onQueue(self::getQueueName());
    }

    public function handle(AffiliateService $service): void
    {
        $service->registerUser($this->user);
    }
}
