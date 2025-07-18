<?php

namespace App\Jobs;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\Advertiser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AdvertiserNotFoundToPending implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $source, $jobID, $isStatusChange;

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
        $this->source = $data['source'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            Methods::customDefault("ADVERTISER NOT FOUND TO PENDING", ucwords($this->source) . " ADVERTISER NOT FOUND TO FETCHING START");

            $advertisersNotFound = Advertiser::select('sid')->where("source", $this->source)->where("is_available", Vars::ADVERTISER_NOT_AVAILABLE)->get()->pluck('sid');
            $advertiserIDs = Advertiser::select('sid')->whereIn("sid", $advertisersNotFound)->where('status', Vars::ADVERTISER_STATUS_NOT_FOUND)->get()->pluck('sid')->toArray();
            if(count($advertiserIDs))
            {
                Advertiser::whereIn("sid", $advertiserIDs)->update([
                    'status' => Vars::ADVERTISER_STATUS_PENDING
                ]);
            }

            Methods::customDefault(null, ucwords($this->source) . " ADVERTISER NOT FOUND TO FETCHING END");
            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);

        } catch (\Exception $exception) {

            Methods::customError("ADVERTISER NOT FOUND TO PENDING", $exception);
            Methods::catchBodyFetchDaily(ucwords($this->source) . " ADVERTISER NOT FOUND TO PENDING", $this->jobID, $this->isStatusChange);

        }
    }
}
