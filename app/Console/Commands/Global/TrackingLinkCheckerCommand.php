<?php

namespace App\Console\Commands\Global;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\AppliedAdvertiserStatusChange;
use App\Jobs\FixTrackingLinkJob;
use App\Models\AdvertiserApply;
use App\Models\AdvertiserPublisher;
use App\Models\FetchDailyData;
use App\Models\Tracking;
use App\Traits\GenerateLink;
use Illuminate\Console\Command;
use App\Models\GenerateLink as GenerateLinkModel;

class TrackingLinkCheckerCommand extends Command
{
    use GenerateLink;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-tracking-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tracking Link Checker If Empty Then Regenerate.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->fixOwnTrackingURL();
        $this->checkNCreateTrackingURL();
        $this->changeTrackingURLStatus();
        $this->updateEmptyClickThroughURLWithoutSubID();
        $this->updateNotEmptyClickThroughURLWithoutSubID();
    }

    private function checkNCreateTrackingURL()
    {
        $advertisers = AdvertiserPublisher::with('advertiser')
                                    ->where('status', AdvertiserPublisher::STATUS_ACTIVE)
                                    ->where(function($query) {
                                        $query->orWhereNull("click_through_url");
                                        $query->orWhere("click_through_url", "=", "");
                                    })->get();

        foreach ($advertisers as $advertiser)
        {
            if(empty($advertiser->publisher_id))
            {
                Methods::customError("Regenerate Tracking URL inside Advertiser Apply Publisher ID not exist.", $advertiser);
            }

            elseif(empty($advertiser->website_id))
            {
                Methods::customError("Regenerate Tracking URL inside Advertiser Apply Website ID not exist.", $advertiser);
            }

            else {

                $queue = Vars::LINK_GENERATE;

                $advertiserCollection = $advertiser->advertiser;

                if(isset($advertiserCollection->id))
                {
                    GenerateLinkModel::updateOrCreate([
                        'advertiser_id' => $advertiser->advertiser->id,
                        'publisher_id' => $advertiser->publisher_id,
                        'website_id' => $advertiser->website_id,
                        'sub_id' => null
                    ], [
                        'name' => 'Tracking Link Job',
                        'path' => 'GenerateTrackingLinkJob',
                        'payload' => collect([
                            'advertiser' => $advertiserCollection,
                            'publisher_id' => $advertiser->publisher_id,
                            'website_id' => $advertiser->website_id
                        ]),
                        'date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                        'queue' => $queue
                    ]);
                }

//                GenerateTrackingLinkJob::dispatch($advertiser->advertiser, $advertiser->publisher_id, $advertiser->website_id)->onQueue($queue);

            }
        }

    }

    private function fixOwnTrackingURL()
    {
       
        $advertisers = AdvertiserPublisher::select('id', 'advertiser_sid as sid', 'publisher_id', 'website_id')->where('status', AdvertiserPublisher::STATUS_ACTIVE)
            ->where(function($query) {
                $query->orWhereNull("tracking_url")
                      ->orWhere("tracking_url", "=", "")
                      ->orWhereNull("tracking_url_short")
                      ->orWhere("tracking_url_short", "=", "");
            })->get();
            
            \Log::channel('broadcast')->info($advertisers);
            \Log::channel('broadcast')->info('fix');
        foreach ($advertisers as $advertiser)
        {
            if(empty($advertiser->publisher_id))
            {
                Methods::customError("Regenerate Tracking URL inside Advertiser Apply Publisher ID not exist.", $advertiser);
            }

            elseif(empty($advertiser->website_id))
            {
                Methods::customError("Regenerate Tracking URL inside Advertiser Apply Website ID not exist.", $advertiser);
            }

            else {

                $queue = Methods::returnAdvertiserQueue($advertiser->source);
                FixTrackingLinkJob::dispatch($advertiser)->onQueue($queue);

            }
        }
        echo "FIX OWN TRACKING URL ENDING";
        echo "\n";
    }

    private function changeTrackingURLStatus()
    {
        echo "CHANGE TRACKING URL STATUS STARTING";
        echo "\n";
        AdvertiserPublisher::select('id')->whereIn('is_tracking_generate', [AdvertiserPublisher::GENERATE_LINK_EMPTY, AdvertiserPublisher::GENERATE_LINK_IN_PROCESS])
                                    ->whereNotNull('click_through_url')
                                    ->whereNotNull('tracking_url')
                                    ->whereNotNull('tracking_url_short')
                                    ->where('click_through_url', '!=', '')
                                    ->where('tracking_url', '!=', '')
                                    ->where('tracking_url_short', '!=', '')
                                    ->where('status', AdvertiserPublisher::STATUS_ACTIVE)
                                    ->chunk(100, function ($advertisers) {
                                        AppliedAdvertiserStatusChange::dispatch($advertisers->pluck('id'))
                                            ->onConnection("redis")
                                            ->onQueue(Vars::ADMIN_WORK);
                                    });

        echo "CHANGE TRACKING URL STATUS ENDING";
        echo "\n";
    }

    private function updateEmptyClickThroughURLWithoutSubID()
    {
        echo "UPDATE EMPTY CLICK THROUGH URL WITHOUT SUB ID STARTING";
        echo "\n";
        $trackings = Tracking::whereNull("click_through_url")->whereNull("sub_id")->whereNotNull("tracking_url_short")->get();
        foreach($trackings as $tracking)
        {
            $advertiser = AdvertiserPublisher::select([
                    'id',
                    'click_through_url'
                ])
                ->where("advertiser_id", $tracking->advertiser_id)
                ->where("publisher_id", $tracking->publisher_id)
                ->where("website_id", $tracking->website_id)
                ->where("status", AdvertiserPublisher::STATUS_ACTIVE)
                ->first();
            if(isset($advertiser->click_through_url))
                $tracking->update([
                    'click_through_url' => $advertiser->click_through_url
                ]);
        }
        echo "UPDATE EMPTY CLICK THROUGH URL WITHOUT SUB ID ENDING";
        echo "\n";
    }

    private function updateNotEmptyClickThroughURLWithoutSubID()
    {
        echo "UPDATE NOT EMPTY CLICK THROUGH URL WITHOUT SUB ID STARTING";
        echo "\n";
        $trackings = Tracking::select([
            'advertiser_id', 'publisher_id', 'website_id', 'id'
        ])->whereNotNull("click_through_url")->whereNull("sub_id")->whereNull("tracking_url_short")->get();
        foreach($trackings as $tracking)
        {
            $advertiser = AdvertiserPublisher::select([
                    'id',
                    'tracking_url_short',
                    'tracking_url',
                ])
                ->where("advertiser_id", $tracking->advertiser_id)
                ->where("publisher_id", $tracking->publisher_id)
                ->where("website_id", $tracking->website_id)
                ->where("status", AdvertiserPublisher::STATUS_ACTIVE)
                ->first();
            if(isset($advertiser->tracking_url_short))
                Tracking::where('id', $tracking->id)->update([
                    'tracking_url_short' => $advertiser->tracking_url_short,
                    'tracking_url' => $advertiser->tracking_url
                ]);;
        }
        echo "UPDATE NOT EMPTY CLICK THROUGH URL WITHOUT SUB ID ENDING";
        echo "\n";
    }
}
