<?php

namespace App\Notifications;

use App\Enum\VerificationCodes;
use App\Models\VerificationCode;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioMessage;
use NotificationChannels\Twilio\TwilioSmsMessage;

final class VerifyByCode extends Notification
{
    public function __construct(private VerificationCode $accountCode)
    {
    }

    public function via(): array
    {
        return match ($this->accountCode->type) {
            VerificationCodes::TYPE_EMAIL => ['mail'],
            VerificationCodes::TYPE_SMS => [TwilioChannel::class],
        };
    }

    public function toMail(): MailMessage
    {
        if ($this->accountCode->action === VerificationCodes::ACTION_RESET) {
            return (new MailMessage())
                ->subject(__('notifications.verification_code.email.reset.subject'))
                ->greeting(__('notifications.verification_code.email.greeting'))
                ->line(new HtmlString(__('notifications.verification_code.code') . ": <strong>{$this->accountCode->code}</strong>"))
                ->line(__('notifications.verification_code.email.no_action'));
        }

        return (new MailMessage())
            ->subject(Lang::get('Verification code'))
            ->line(Lang::get('Please enter this code in your account:'))
            ->line(new HtmlString('<strong>' . $this->accountCode->code . '</strong>'))
            ->line(Lang::get('If you did not request a verification code, no further action is required.'));
    }

    public function toNexmo(): NexmoMessage
    {
        return (new NexmoMessage())
            ->content(__('notifications.verification_code.code') . ": {$this->accountCode->code}");
    }

//    public function toSmscRu(mixed $notifiable): SmscRuMessage
//    {
//        return SmscRuMessage::create('Your verification Code is: '.$this->accountCode->code);
//    }

    public function toTwilio(): TwilioMessage
    {
        return (new TwilioSmsMessage())
            ->content(__('notifications.verification_code.code') . ": {$this->accountCode->code}");
    }
}
