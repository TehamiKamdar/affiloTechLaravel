<?php

namespace App\Jobs;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\Advertiser;
use App\Models\AdvertiserApply;
use App\Models\AdvertiserPublisher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AdvertiserDeleteFromNetwork implements ShouldQueue
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

            Methods::customDefault("ADVERTISER DELETE FROM NETWORK", ucwords($this->source) . " ADVERTISER DELETE FROM NETWORK FETCHING START");

            $this->advertiserNotFound();
            $this->advertiserFoundAgain();
            $this->advertiserApplyActiveInsteadOfOnHold();

            Methods::customDefault(null, ucwords($this->source) . " ADVERTISER DELETE FROM NETWORK FETCHING END");
            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);

        } catch (\Exception $exception) {

            Methods::customError("ADVERTISER DELETE TO FROM NETWORK", $exception);
            Methods::catchBodyFetchDaily(ucwords($this->source) . " ADVERTISER DELETE FROM NETWORK", $this->jobID, $this->isStatusChange);

        }
    }

    private function advertiserNotFound()
    {
        $advertisers = Advertiser::where("source", $this->source)
            ->where("is_available", Vars::ADVERTISER_NOT_AVAILABLE)
            ->where("status", '!=', Vars::ADVERTISER_STATUS_NOT_FOUND);

        if($advertisers->count())
        {
            $advertisers->update([
                'status' => Vars::ADVERTISER_STATUS_NOT_FOUND
            ]);

            UpdateAdvertiserStatusOnFetchTimeJob::dispatch([
                "a_id" => $advertisers->get()->pluck("sid"),
                "status" => AdvertiserPublisher::STATUS_HOLD,
                "source" => $this->source
            ])->onQueue(Vars::ADMIN_WORK);
        }
    }

    private function advertiserFoundAgain()
    {
        $advertisers = Advertiser::where("source", $this->source)
            ->where("is_available", Vars::ADVERTISER_AVAILABLE)
            ->where("status", Vars::ADVERTISER_STATUS_NOT_FOUND);

        if($advertisers->count())
        {
            $advertisers->update([
                'status' => Vars::ADVERTISER_STATUS_ACTIVE
            ]);

            UpdateAdvertiserStatusOnFetchTimeJob::dispatch([
                "a_id" => $advertisers->get()->pluck("sid"),
                "status" => AdvertiserPublisher::STATUS_PENDING,
                "source" => $this->source
            ])->onQueue(Vars::ADMIN_WORK);
        }
    }

    private function advertiserApplyActiveInsteadOfOnHold()
    {
        $advertisers = Advertiser::select('sid')
                                ->whereHas('advertiser_applies_multi', function($q){
                                    $q->whereIn('status', [AdvertiserPublisher::STATUS_ACTIVE, AdvertiserPublisher::STATUS_PENDING]);
                                })
                                ->where("source", $this->source)
                                ->where("is_available", Vars::ADVERTISER_NOT_AVAILABLE)
                                ->where("status", Vars::ADVERTISER_STATUS_NOT_FOUND)
                                ->get();

        if(count($advertisers))
        {
            foreach ($advertisers->pluck("sid")->chunk(100) as $advertiserSID)
            {
                UpdateAdvertiserStatusOnFetchTimeJob::dispatch([
                    "a_id" => $advertiserSID,
                    "status" => AdvertiserPublisher::STATUS_HOLD,
                    "source" => $this->source
                ])->onQueue(Vars::ADMIN_WORK);
            }
        }
    }
}
