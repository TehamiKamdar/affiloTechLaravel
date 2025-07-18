<?php

namespace App\Console\Commands\Global;

use App\Models\Notification;
use Illuminate\Console\Command;

class ClearNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All notification will be deleted after 3 months.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $threeMonthsAgo = now()->subDays(31);
        Notification::whereDate("created_at", "<=", $threeMonthsAgo)->delete();
    }
}
