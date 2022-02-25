<?php

namespace App\Listeners\User;

use App\Events\User\UserAdminLogin;
use App\Events\User\UserCreated;
use App\Events\User\UserUpdated;
use App\Listeners\AbstractEventsSubscriber;
use App\Listeners\User\Created\BonusTagsCreator;
use App\Listeners\User\Created\UserCreatedAffiliateNotification;
use App\Listeners\User\Created\VerificationCodeCreator;
use App\Listeners\User\Updated\UserDocumentsClear;
use App\Listeners\User\Updated\UserDocumentsUploaded;

class UserEventsSubscriber extends AbstractEventsSubscriber
{
    protected array $listen = [
        UserCreated::class => [
            VerificationCodeCreator::class,
            BonusTagsCreator::class,
            UserCreatedAffiliateNotification::class,
        ],

        UserUpdated::class => [
            UserDocumentsClear::class,
            UserDocumentsUploaded::class,
        ],

        UserAdminLogin::class => [
            UserAdminLoginHandler::class,
        ]
    ];
}
