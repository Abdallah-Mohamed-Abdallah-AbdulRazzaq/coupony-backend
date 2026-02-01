<?php

namespace App\Domain\User\Enums;

enum NotificationStatus: string
{
    case READ = 'read';
    case PENDING = 'pending';
    case FAILED = 'failed';
    case SENT = 'sent';
}

