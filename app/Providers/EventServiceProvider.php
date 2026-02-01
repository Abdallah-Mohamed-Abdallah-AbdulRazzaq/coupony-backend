<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegistered::class => [
            SendWelcomeEmail::class,              // Queued: emails queue
            CreateUserPreferences::class,          // Queued: default queue
            SendEmailVerification::class,          // Queued: emails queue
            AwardRegistrationPoints::class,        // Queued: default queue
            TrackUserRegistration::class,          // Sync: immediate
            SendAdminNewUserNotification::class,   // Queued: default queue
        ],
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
