<?php

namespace App\Console\Commands\Global;

use App\Helper\Static\Vars;
use App\Jobs\Mail\Account\ApproveJob;
use App\Jobs\Mail\Account\HoldJob;
use App\Jobs\Mail\Account\RejectJob;
use App\Jobs\Mail\Advertiser\JoinedAdvertiserHoldJob;
use App\Jobs\Mail\Advertiser\JoinedAdvertiserRejectedJob;
use App\Jobs\Mail\Payment\InvoiceGenerateJob;
use App\Jobs\Mail\User\SendEmailVerifyJob;
use App\Models\EmailJob;
use Illuminate\Console\Command;

class SendEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email Globally';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $job = EmailJob::select([
                    'id', 'payload', 'path', 'status'
                ])->where("date", "<=", now()->format(Vars::CUSTOM_DATE_FORMAT))
                ->where("status", 1)
                ->first();

        if(isset($job->id))
        {
            $queue = Vars::SEND_EMAIL;
            $payload = json_decode($job->payload);
            switch ($job->path)
            {
                case "InvoiceGenerateJob":
                    InvoiceGenerateJob::dispatch($payload)->onQueue($queue);
                    break;

                case "JoinedAdvertiserHoldJob":
                    JoinedAdvertiserHoldJob::dispatch($payload)->onQueue($queue);
                    break;

                case "JoinedAdvertiserRejectedJob":
                    JoinedAdvertiserRejectedJob::dispatch($payload)->onQueue($queue);
                    break;

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

            $job->update([
                'status' => 0
            ]);

            EmailJob::where('status', 1)->update([
                'date' => now()->addSeconds(20)->format(Vars::CUSTOM_DATE_FORMAT)
            ]);
        }
    }
}
