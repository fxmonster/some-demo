<?php

namespace App\Notifications;

use App\Helpers\ImageHelper;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserDocumentsUploaded extends Notification
{
    public function __construct(private User $user)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $email = (new MailMessage())
            ->template('notifications::user_documents')
            ->subject($this->user->id);

        foreach ($this->user->documents as $document) {
            $email->line(ImageHelper::getFullImageUrl(UserDocument::IMG_PATH, $document->image));
        }

        return $email;
    }
}
