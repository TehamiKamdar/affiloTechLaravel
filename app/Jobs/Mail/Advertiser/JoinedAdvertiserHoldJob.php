<?php

namespace App\Jobs\Mail\Advertiser;

use App\Mail\JoinedAdvertiserHoldEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class JoinedAdvertiserHoldJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $advertiser;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($advertiser)
    {
        $this->advertiser = $advertiser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->advertiser->publisher->email)->bcc(env("BCC_MAIL"))->send(new JoinedAdvertiserHoldEmail($this->advertiser));
    }
}
