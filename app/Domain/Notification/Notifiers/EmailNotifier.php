<?php

namespace App\Domain\Notification\Notifiers;

use App\Domain\Notification\Contracts\NotifierInterface;
use App\Domain\Notification\Mail\NotificationEmail;
use App\Domain\Notification\Models\Notification;
use App\Domain\User\Models\User;
use Mail;

class EmailNotifier implements NotifierInterface
{
    public function send(Notification $notification, User $user): void
    {
        Mail::to($user->email)
            ->queue(new NotificationEmail($notification, $user));
    }
}
