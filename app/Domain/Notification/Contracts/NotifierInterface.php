<?php

namespace App\Domain\Notification\Contracts;

use App\Domain\Notification\Models\Notification;
use App\Domain\User\Models\User;

interface NotifierInterface
{
    /**
     * Send notification via this channel.
     */
    public function send(Notification $notification, User $user): void;
}
