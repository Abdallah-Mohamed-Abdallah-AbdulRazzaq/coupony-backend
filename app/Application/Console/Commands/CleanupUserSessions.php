<?php

namespace App\Application\Console\Commands;

use App\Domain\User\Models\Session;
use Illuminate\Console\Command;

class CleanupUserSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired user sessions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Session::whereNotNull('expires_at')
            ->where('expires_at', '<', now()->subDays(30))
            ->delete();
    }
}
