<?php

namespace App\Console\Commands\Global;

use App\Models\TrackingDetail;
use Illuminate\Console\Command;

use App\Helper\Static\Vars;
use App\Models\Advertiser;
use App\Models\AdvertiserApply;
use App\Models\FetchDailyData;
use App\Models\Setting;
use App\Models\Tracking;
use App\Models\User;
use App\Models\Website;
use Illuminate\Support\Facades\DB;

class DeleteLinksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'links:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete and archive links from various tracking tables.';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $chunkSize = 1000; // Number of tracking records to process in each chunk

        $trackingCount = DB::table('trackings')->where('is_deleted', '1')->take(10)->pluck('id')->count();
        if($trackingCount)
        {
            DB::transaction(function () use ($chunkSize) {
                $this->moveTrackingLinks($chunkSize);
            });
        }
        else
        {
            $trackingDetailCount = DB::table('tracking_details')->where('is_deleted', '1')->take(10)->pluck('id')->count();
            if($trackingDetailCount)
            {
                DB::transaction(function () use ($chunkSize) {
                    $this->moveTrackingLinkDetails($chunkSize);
                });
            }
            else
            {
                $deeplinkTrackingCount = DB::table('deeplink_trackings')->where('is_deleted', '1')->take(10)->pluck('id')->count();
                if($deeplinkTrackingCount)
                {
                    DB::transaction(function () use ($chunkSize) {
                        $this->moveDeepTrackingLinks($chunkSize);
                    });
                }
                else
                {
                    $deepTrackingDetailCount = DB::table('deeplink_tracking_details')->where('is_deleted', '1')->take(10)->pluck('id')->count();
                    if($deepTrackingDetailCount)
                    {
                        DB::transaction(function () use ($chunkSize) {
                            $this->moveDeepTrackingLinkDetails($chunkSize);
                        });
                    }
                    else
                    {
                        $couponTrackingCount = DB::table('coupon_trackings')->where('is_deleted', '1')->take(10)->pluck('id')->count();
                        if($couponTrackingCount)
                        {
                            DB::transaction(function () use ($chunkSize) {
                                $this->moveCouponTrackingLinks($chunkSize);
                            });
                        }
                        else
                        {
                            $couponTrackingDetailCount = DB::table('coupon_tracking_details')->where('is_deleted', '1')->take(10)->pluck('id')->count();
                            if($couponTrackingDetailCount)
                            {
                                DB::transaction(function () use ($chunkSize) {
                                    $this->moveCouponTrackingLinkDetails($chunkSize);
                                });
                            }
                        }

                    }
                }
            }
        }
    }

    protected function moveTrackingLinks($chunkSize)
    {
        // Columns for each table
        $trackingColumns = [
            'id', 'advertiser_id', 'publisher_id', 'website_id',
            'click_through_url', 'tracking_url_long', 'tracking_url_short',
            'tracking_url', 'sub_id', 'hits', 'unique_visitor', 'created_at', 'updated_at'
        ];

        $sourceTable = "trackings";
        $destTable = "del_trackings";
        $this->moveData($sourceTable, $chunkSize, $destTable, $trackingColumns);
    }

    protected function moveTrackingLinkDetails($chunkSize)
    {
        // Columns for each table
        $trackingDetailColumns = [
            'id', 'advertiser_id', 'publisher_id', 'website_id', 'tracking_id',
            'ip_address', 'operating_system', 'browser', 'device',
            'referer_url', 'country', 'iso2', 'region', 'city',
            'zipcode', 'created_at', 'updated_at'
        ];

        $sourceTable = "tracking_details";
        $destTable = "del_tracking_details";
        $this->moveData($sourceTable, $chunkSize, $destTable, $trackingDetailColumns);
    }

    protected function moveDeepTrackingLinks($chunkSize)
    {
        // Columns for each table
        $deeplinkTrackingColumns = [
            'id', 'advertiser_id', 'publisher_id', 'website_id',
            'click_through_url', 'tracking_url_long', 'tracking_url',
            'sub_id', 'hits', 'unique_visitor', 'created_at', 'updated_at'
        ];

        $sourceTable = "deeplink_trackings";
        $destTable = "del_deeplink_trackings";
        $this->moveData($sourceTable, $chunkSize, $destTable, $deeplinkTrackingColumns);
    }

    protected function moveDeepTrackingLinkDetails($chunkSize)
    {
        // Columns for each table
        $trackingDetailColumns = [
            'id', 'advertiser_id', 'publisher_id', 'website_id', 'tracking_id',
            'ip_address', 'operating_system', 'browser', 'device',
            'referer_url', 'country', 'iso2', 'region', 'city',
            'zipcode', 'created_at', 'updated_at'
        ];

        $sourceTable = "deeplink_tracking_details";
        $destTable = "del_deeplink_tracking_details";
        $this->moveData($sourceTable, $chunkSize, $destTable, $trackingDetailColumns);
    }

    protected function moveCouponTrackingLinks($chunkSize)
    {
        // Columns for each table
        $couponTrackingColumns = [
            'id', 'advertiser_id', 'publisher_id', 'website_id',
            'click_through_url', 'tracking_url', 'hits', 'unique_visitor', 'created_at', 'updated_at'
        ];

        $sourceTable = "coupon_trackings";
        $destTable = "del_coupon_trackings";
        $this->moveData($sourceTable, $chunkSize, $destTable, $couponTrackingColumns);
    }

    protected function moveCouponTrackingLinkDetails($chunkSize)
    {
        // Columns for each table
        $trackingDetailColumns = [
            'id', 'advertiser_id', 'publisher_id', 'website_id', 'tracking_id',
            'ip_address', 'operating_system', 'browser', 'device',
            'referer_url', 'country', 'iso2', 'region', 'city',
            'zipcode', 'created_at', 'updated_at'
        ];

        $sourceTable = "coupon_tracking_details";
        $destTable = "del_coupon_tracking_details";
        $this->moveData($sourceTable, $chunkSize, $destTable, $trackingDetailColumns);
    }

    protected function moveData($sourceTable, $chunkSize, $destTable, $columns)
    {
        $records = DB::table($sourceTable)
                        ->where('is_deleted', 1)
                        ->take($chunkSize)
                        ->get();

        $destRecords = [];
        foreach($records as $record)
        {
            $destRecord = [];
            foreach ($columns as $column) {
                $destRecord[$column] = $record->$column;
            }
            $destRecords[] = $destRecord;
        }

        DB::table($destTable)->insert($destRecords);

        // Delete the moved records
        $recordIds = array_column($records->toArray(), 'id');
        DB::table($sourceTable)->whereIn('id', $recordIds)->delete();

    }
}
