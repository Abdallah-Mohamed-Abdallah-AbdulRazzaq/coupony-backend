<?php

namespace App\Domain\User\Enums;

enum NotificationChannels: string
{
    case IN_APP = 'in_app';
    case EMAIL = 'email';
    case SMS = 'sms';
    case PUSH = 'push';
}

