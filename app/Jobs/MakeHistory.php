<?php

namespace App\Jobs;

use App\Helper\Static\Methods;
use App\Models\AdvertiserApply;
use App\Models\AdvertiserPublisher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class MakeHistory implements ShouldQueue
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

            $agent = new Agent();

            $device = null;

            if($agent->isDesktop())
            {
                $device = "desktop";
            }
            elseif($agent->isTablet())
            {
                $device = "tablet";
            }
            elseif($agent->isPhone())
            {
                $device = "phone";
            }

            $ip = $this->data['ip'];
            $ip = ($ip == "::1" || $ip == "127.0.0.1") ? "110.93.196.117" : $ip;
            $location = Location::get($ip);

            $trackingModel = $this->data['model_tracking'];
            $trackingDetailModel = $this->data['model_tracking_detail'];
            $tracking = $trackingModel::find($this->data['tracking_id']);
            if(empty($tracking))
            {
                $trackingModel = str_replace("App\Models\\", "App\Models\Del", $trackingModel);
                $tracking = $trackingModel::find($this->data['tracking_id']);
            }

            if(empty($tracking))
            {
                $appliedAdvertiser = AdvertiserPublisher::find($this->data['active_advertiser_apply_id']);
                if($appliedAdvertiser)
                {
                    $tracking = $trackingModel::where('advertiser_id', $appliedAdvertiser->advertiser_id)->where('publisher_id', $appliedAdvertiser->publisher_id)->where('website_id', $appliedAdvertiser->website_id)->first();
                    if(empty($tracking))
                    {
                        $trackingModel = str_replace("App\Models\Del\\", "App\Models", $trackingModel);
                        $tracking = $trackingModel::where('advertiser_id', $appliedAdvertiser->advertiser_id)->where('publisher_id', $appliedAdvertiser->publisher_id)->where('website_id', $appliedAdvertiser->website_id)->first();
                    }
                }
            }

            $tracking->increment('hits');

            $activeAdvertiser = AdvertiserPublisher::find($this->data['active_advertiser_apply_id']);

            $checkUniqueVisitor = $trackingDetailModel::where("ip_address", $ip)->where('tracking_id', $tracking->id)->count();

            if($checkUniqueVisitor == 0)
                $tracking->increment('unique_visitor');

            $referer = request()->headers->get('referer');

            $trackingDetailModel::create([
                'advertiser_id' => $activeAdvertiser->advertiser_id,
                'website_id' => $activeAdvertiser->website_id,
                'publisher_id' => $activeAdvertiser->publisher_id,
                'tracking_id' => $tracking->id,
                'ip_address' => $ip,
                'operating_system' => $agent->platform(),
                'browser' => $agent->browser(),
                'device' => $device,
                'referer_url' => $referer,
                'country' => isset($location->countryName) && is_string($location->countryName) ? $location->countryName : null,
                'iso2' => isset($location->countryCode) && is_string($location->countryCode) ? $location->countryCode : null,
                'region' => isset($location->regionName) && is_string($location->regionName) ? $location->regionName : null,
                'city' => isset($location->cityName) && is_string($location->cityName) ? $location->cityName : null,
                'zipcode' => $location->zipCode ?? null
            ]);

            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange, 'history');

        } catch (\Throwable $exception) {

            Methods::catchBodyFetchDaily("MAKE HISTORY JOB", $exception, $this->jobID, 'history');

        }

    }
}
