<?php

namespace App\Console\Commands\Global;

use App\Helper\Static\Vars;
use App\Models\AdvertiserApply;
use App\Models\AdvertiserPublisher;
use App\Models\GenerateLink as GenerateLinkModel;
use App\Models\Tracking;
use Illuminate\Console\Command;

class TrackingURLCheckerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checker-tracking-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Advertiser Apply URL';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->clickThroughURLEmpty();
        $this->clickThroughURLNotEmptyGenerateLinkInProcess();
    }

    protected function clickThroughURLEmpty()
    {
        Tracking::select(['id', 'advertiser_id', 'publisher_id', 'website_id', 'sub_id'])
            ->with('advertiser')
            ->where("created_at", "<", now()->toDateTimeString())
            ->whereNull("click_through_url")
            ->whereNotNull("tracking_url_short")
            ->whereNotNull("tracking_url_long")
            ->chunk(Vars::LIMIT_200, function ($advertisers) {
                foreach ($advertisers as $advertiser)
                {
                    $advertiserCollection = $advertiser->advertiser;

                    if(isset($advertiserCollection->id))
                    {
                        $queue = Vars::LINK_GENERATE;
                        GenerateLinkModel::updateOrCreate([
                            'advertiser_id' => $advertiser->advertiser->id,
                            'publisher_id' => $advertiser->publisher_id,
                            'website_id' => $advertiser->website_id,
                            'sub_id' => $advertiser->sub_id,
                            'date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                        ],[
                            'name' => 'Tracking Link Job',
                            'path' => 'GenerateTrackingLinkJob',
                            'payload' => collect([
                                'advertiser' => $advertiserCollection,
                                'publisher_id' => $advertiser->publisher_id,
                                'website_id' => $advertiser->website_id,
                                'sub_id' => $advertiser->sub_id
                            ]),
                            'queue' => $queue
                        ]);
                    }
                    else
                    {
                        $advertiser->delete();
                    }

                }
            });
    }

    protected function clickThroughURLNotEmptyGenerateLinkInProcess()
    {
        AdvertiserPublisher::select('id')->where("status", AdvertiserPublisher::STATUS_ACTIVE)
            ->whereIn("is_tracking_generate", [AdvertiserPublisher::GENERATE_LINK_IN_PROCESS, AdvertiserPublisher::GENERATE_LINK_EMPTY])
            ->where(function($query) {
                $query->orWhereNotNull("click_through_url");
                $query->orWhere("click_through_url", "!=", "");
            })
            ->update([
                'is_tracking_generate' => AdvertiserPublisher::GENERATE_LINK_COMPLETE
            ]);
    }

}
