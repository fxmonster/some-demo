<?php

namespace App\Notifications;

use App\Enum\VerificationCodes;
use App\Models\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioMessage;
use NotificationChannels\Twilio\TwilioSmsMessage;

final class NewUserPassword extends Notification
{
    public function __construct(
        private ResetPassword $resetPassword,
        private string $password
    ) {
    }

    public function via(): array
    {
        return match ($this->resetPassword->method) {
            VerificationCodes::TYPE_EMAIL => ['mail'],
            VerificationCodes::TYPE_SMS => [TwilioChannel::class],
        };
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->subject(__('notifications.verification_code.password.subject'))
            ->greeting(__('notifications.verification_code.email.greeting'))
            ->line(new HtmlString(__('notifications.verification_code.password.recovery') . ": <strong>{$this->password}</strong>"))
            ->line(__('notifications.verification_code.email.no_action'));
    }

    public function toNexmo(): NexmoMessage
    {
        return (new NexmoMessage())
            ->content(__('notifications.verification_code.password.subject').  ": $this->password");
    }


    public function toTwilio(): TwilioMessage
    {
        return (new TwilioSmsMessage())
            ->content(__('notifications.verification_code.password.subject').  ": $this->password");
    }

//    public function toSmscRu(mixed $notifiable): SmscRuMessage
//    {
//        return SmscRuMessage::create("Your new Password is: $this->password");
//    }

}
