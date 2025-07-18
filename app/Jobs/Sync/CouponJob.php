<?php

namespace App\Jobs\Sync;

use App\Helper\Static\Methods;
use App\Models\Advertiser;
use App\Models\Coupon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CouponJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $source, $page, $data, $jobID, $isStatusChange;

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
        $this->source = $data['source']['source'] ?? $data['source'];
        $this->page = $data['page'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try {

            $url = env("APP_SERVER_API_URL");
            $response = Http::get("{$url}api/sync-offers?source={$this->source}&page={$this->page}");
            if($response->ok())
            {
                $coupons = $response->json();
                foreach ($coupons['data'] as $coupon)
                {
                    $data = Coupon::where("promotion_id", $coupon['promotion_id'])->where('source', $coupon['source'])->first();

                    if(isset($data->id))
                    {
                        $data->update($coupon);
                    }
                    else
                    {
                        Coupon::create($coupon);
                    }
                }
            }

            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);

        } catch (\Throwable $exception) {

            Methods::catchBodyFetchDaily("SYNC COUPON JOB", $exception, $this->jobID);

        }

    }
}
