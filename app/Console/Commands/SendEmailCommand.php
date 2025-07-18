<?php
namespace App\Console\Commands;

use App\Helper\Vars;
use App\Jobs\Mail\Account\ApproveJob;
use App\Jobs\Mail\Account\HoldJob;
use App\Jobs\Mail\Account\RejectJob;
use App\Jobs\Mail\User\SendEmailVerifyJob;
use App\Models\EmailJob;
use Illuminate\Console\Command;

class SendEmailCommand extends Command
{
    protected $signature = "app:send-email-command";
    protected $description = "Send Email Globally";

    public function handle()
    {
        $jobs = EmailJob::select(['id', 'payload', 'path', 'status'])
            ->where('date', '<=', now()->format(Vars::CUSTOM_DATE_FORMAT_2))
            ->where('status', EmailJob::STATUS_ACTIVE)
            ->where('is_processing', EmailJob::IS_PROCESSING_NOT_START)
            ->take(5)
            ->get();

        $completedJobIds = [];

        foreach ($jobs as $job) {
            $queue = Vars::SEND_EMAIL;
            $payload = json_decode($job->payload);

            switch ($job->path) {
                case "HoldJob":
                    HoldJob::dispatch($payload)->onQueue($queue);
                    break;
                case "RejectJob":
                    RejectJob::dispatch($payload)->onQueue($queue);
                    break;
                case "ApproveJob":
                    ApproveJob::dispatch($payload)->onQueue($queue);
                    break;
                case "SendEmailVerifyJob":
                    SendEmailVerifyJob::dispatch($payload)->onQueue($queue);
                    break;
                default:
                    break;
            }

            $completedJobIds[] = $job->id;
        }

        if (!empty($completedJobIds)) {
            EmailJob::whereIn('id', $completedJobIds)
                ->update([
                    'status' => EmailJob::STATUS_COMPLETED,
                    'is_processing' => EmailJob::IS_PROCESSING_COMPLETED,
                ]);
        }

        EmailJob::where('status', EmailJob::STATUS_ACTIVE)
            ->update(['date' => now()->addSeconds(20)->format(Vars::CUSTOM_DATE_FORMAT_2)]);
    }
}
