<?php

namespace App\Jobs\Sync;

use App\Helper\Static\Methods;
use App\Models\CouponTracking;
use App\Models\DeeplinkTracking;
use App\Models\Tracking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class LinkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id, $type, $jobID, $isStatusChange;

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
        $this->id = $data['id'];
        $this->type = $data['type'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            switch ($this->type)
            {
                case "tracking":
                    $link = Tracking::where("id", $this->id)->first();
                    break;

                case "deeplink":
                    $link = DeeplinkTracking::where("id", $this->id)->first();
                    break;

                case "coupon_link":
                    $link = CouponTracking::where("id", $this->id)->first();
                    break;

                default:
                    break;
            }
            $url = env("APP_SERVER_API_URL");
            Http::post("{$url}api/sync-link", [
                'data' => $link->toArray(),
                'type' => $this->type
            ]);

            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);

        } catch (\Throwable $exception) {

            Methods::catchBodyFetchDaily("SYNC LINK JOB", $exception, $this->jobID);

        }
    }
}
