<?php

namespace App\Application\Console\Commands;

use App\Domain\User\Models\Otp;
use Illuminate\Console\Command;

class CleanupExpiredOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired OTP records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = Otp::where('expires_at', '<', now()->addMicroseconds(1))->delete();

        $this->info("Cleaned up {$deleted} expired OTP records.");
    }
}
