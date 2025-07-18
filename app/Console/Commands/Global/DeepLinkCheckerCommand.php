<?php

namespace App\Console\Commands\Global;

use App\Helper\DeeplinkGenerate;
use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\DeeplinkGenerateJob;
use App\Models\Advertiser;
use App\Models\DeeplinkTracking;
use App\Models\FetchDailyData;
use App\Models\GenerateLink as GenerateLinkModel;
use Illuminate\Console\Command;
use Predis\Client;

class DeepLinkCheckerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-deep-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deep Link Checker If Empty Then Regenerate.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->checkNCreateDeepURL();
    }

    private function checkNCreateDeepURL()
    {
        $deeplinks = DeeplinkTracking::select(['advertiser_id', 'publisher_id', 'website_id', 'landing_url', 'sub_id', 'click_through_url', 'tracking_url', 'tracking_url_long'])->where(function($query) {
            $query->orWhereNull('click_through_url')->orWhere('click_through_url', '');
        })->get();
        foreach ($deeplinks as $deeplink)
        {
            if(empty($deeplink->publisher_id))
            {
                Methods::customError("Regenerate Deep Link URL inside Deep Link Tracking Publisher ID not exist.", $deeplink);
            }

            elseif(empty($deeplink->website_id))
            {
                Methods::customError("Regenerate Deep Link URL inside Deep Link Tracking Website ID not exist.", $deeplink);
            }

            else {

                $queue = Vars::LINK_GENERATE;

                GenerateLinkModel::updateOrCreate([
                    'advertiser_id' => $deeplink->advertiser_id,
                    'publisher_id' => $deeplink->publisher_id,
                    'website_id' => $deeplink->website_id,
                    'landing_url' => $deeplink->landing_url,
                    'sub_id' => $deeplink->sub_id,
                    'date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                ],[
                    'name' => 'Deep Link Job',
                    'path' => 'DeeplinkGenerateJob',
                    'payload' => collect($deeplink->toArray()),
                    'queue' => $queue
                ]);

//                DeeplinkGenerateJob::dispatch($deeplink->toArray())
//                    ->onQueue(Vars::ADMIN_WORK);

            }
        }
    }

}
