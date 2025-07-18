<?php

namespace App\Jobs\Sync;

use App\Helper\Static\Methods;
use App\Models\Advertiser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class AdvertiserCustomJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data, $jobID, $isStatusChange;

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
        $this->data = $data;
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
            $response = Http::get("{$url}api/sync-advertisers?advertiser_id={$this->data['advertiser_id']}&source={$this->data['source']}");
            if($response->ok()) {

                $advertiser = $response->json();
                $advertiser = $advertiser[0] ?? null;

                if($advertiser) {

                    $data = Advertiser::where("advertiser_id", $this->data['advertiser_id'])->where('source', $this->data['source'])->first();
                    $advertiser['api_advertiser_id'] = $advertiser['id'];
                    if(isset($data->id))
                    {
                        unset($advertiser['status']);
                        unset($advertiser['sid']);
                        $data->update($advertiser);
                    }
                    else
                    {
                        Advertiser::create($advertiser);
                    }

                }

            }

            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);

        } catch (\Throwable $exception) {

            Methods::catchBodyFetchDaily("SYNC ADVERTISER JOB", $exception, $this->jobID);

        }
    }
}
