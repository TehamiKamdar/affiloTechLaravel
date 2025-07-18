<?php

namespace App\Console\Commands\Global;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\Clicks;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanLinksCommand extends Command
{
    protected $signature = 'clean:links';
    protected $description = 'Move the link data for Advertisers and Advertiser Apply whose status is set to rejected.';

    public function handle()
    {
        Methods::trackingClicks("CLEAN LINKS", "Clean Link Command Start");

        $trackingCount = DB::table("tracking_details")->select('id')->where("is_new", 1)->count();
    
        if($trackingCount > 20) {
           
            $checkTracking = $this->processTrackingData("tracking_details", "tracking");
             
            $checkDeepTracking = true; // Initialize to true

            if (!$checkTracking) {
                $checkDeepTracking = $this->processTrackingData("deeplink_tracking_details", "deeplink");
            }

            if(!$checkDeepTracking) {
                $this->processTrackingData("coupon_tracking_details", "coupon");
            }
        } else {
            $checkDeepTracking = $this->processTrackingData("deeplink_tracking_details", "deeplink");
            $checkTracking = true; // Initialize to true

            if (!$checkDeepTracking) {
                $checkTracking = $this->processTrackingData("tracking_details", "tracking");
            }

            if(!$checkTracking) {
                $this->processTrackingData("coupon_tracking_details", "coupon");
            }
        }

        Methods::trackingClicks("CLEAN LINKS", "Clean Link Command Finish");
    }

    protected function processTrackingData($detailTable, $linkType)
    {
        // Check if there are any new records to process
        $count = DB::table($detailTable)
            ->select('id')
            ->where('is_deleted', 0)
            ->where('is_new', 1)
            ->take(3)
            ->count();
       

        if ($count === 0) {
            return false; // No new records to process
        }

        $limit = Vars::LIMIT_50;

        try {
            // Fetch records in one batch
          $records = DB::table($detailTable)
                ->select(
                    'publisher_id',
                    'advertiser_id',
                    'website_id',
                    'id',
                    'created_at',
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as total_clicks')
                )
                ->where('is_deleted', 0)
                ->where('is_new', 1)
                ->groupBy('publisher_id', 'website_id', 'advertiser_id', 'date')
                ->orderBy('date', 'DESC')
                ->limit($limit)
                ->get();


            foreach ($records as $data) {

                $data->link_type = $linkType;
                $data->detail_table = $detailTable;

                Clicks::updateOrCreate([
                    "path" => "MakeClickJob",
                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                    "date" => Vars::CUSTOM_DATE_FORMAT_2,
                    "advertiser_id" => $data->advertiser_id,
                    'website_id' => $data->website_id,
                    'publisher_id' => $data->publisher_id,
                    "queue" => Vars::CLICKS,
                    "source" => Vars::GLOBAL
                ], [
                    "name" => "Make the Clicks History.",
                    "payload" => json_encode($data),
                    "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                ]);

            }

            return true;

        } catch (\Exception $e) {
            Log::error("Error processing records in {$detailTable}: {$e->getMessage()}");
            return false;
        }
    }
}
