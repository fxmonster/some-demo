<?php

namespace App\Listeners\User\Created;

use App\Enum\VerificationCodes;
use App\Events\User\UserCreated;
use App\Events\VerificationCode\VerificationCodeCreated;
use App\Models\VerificationCode;
use RuntimeException;
use function event;

final class VerificationCodeCreator
{
    public function handle(UserCreated $event): void
    {
        $accountVerificationCode = new VerificationCode();
        $accountVerificationCode->user()->associate($event->user);
        $accountVerificationCode->type = VerificationCodes::TYPE_SMS;
        $accountVerificationCode->action = VerificationCodes::ACTION_VERIFY;
        $accountVerificationCode->save();

        if ($error = event(new VerificationCodeCreated($accountVerificationCode), [], true)) {
            throw new RuntimeException($error);
        }
    }
}
