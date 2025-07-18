<?php

namespace App\Jobs\Sync;

use App\Helper\Static\Methods;
use App\Models\User;
use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class UserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id, $website_id, $jobID, $isStatusChange;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->jobID = $data['job_id'];
        $this->isStatusChange = $data['is_status_change'];
        unset($data['job_id']);
        unset($data['is_status_change']);
        $this->user_id = $data['user_id'];
        $this->website_id = $data['website_id'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $user = User::where("id", $this->user_id)->first();
            $website = Website::where("id", $this->website_id)->first();
            $url = env("APP_SERVER_API_URL");
            Http::post("{$url}api/sync-users", [
                'user' => $user->toArray(),
                'website' => $website->toArray(),
            ]);
            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);
        } catch (\Exception $exception)
        {
            Methods::catchBodyFetchDaily("SYNC USER JOB", $exception, $this->jobID);
        }
    }
}
