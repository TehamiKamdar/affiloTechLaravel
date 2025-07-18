<?php

namespace App\Helper;

use App\Models\Advertiser;
use App\Models\AdvertiserPublisher;
use App\Models\Mix;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;

class PublisherData
{
    public static function getAdvertisersAccess(): bool
    {
        return Gate::check('ext_publisher_my_advertiser') || Gate::check('ext_publisher_find_advertiser');
    }
    public static function getReportsAccess(): bool
    {
        return Gate::check('ext_publisher_reports_performance') || Gate::check('ext_publisher_reports_transaction');
    }
    public static function getLinksAccess(): bool
    {
        return Gate::check('publisher_links_banners') || Gate::check('publisher_links_text_n_emails') ||
            Gate::check('ext_publisher_links_coupons') || Gate::check('publisher_links_products') ||
            Gate::check('publisher_links_brand_datafeeds');
    }
    public static function getPaymentsAccess(): bool
    {
        return Gate::check('publisher_payments_summary') || Gate::check('publisher_payments_details') ||
            Gate::check('publisher_payments_transaction_inquiries');
    }
    public static function isDashboardActive()
    {
        if(request()->is("publisher/dashboard"))
        {
            return "active";
        }
        return "";
    }
    public static function isAdvertiserActive()
    {
        if(request()->is("publisher/my-advertisers") || request()->is("publisher/find-advertisers") || request()->is("publisher/advertiser-detail/*"))
        {
            return "active";
        }
        return "";
    }
    public static function isReportActive()
    {
        if(request()->is("publisher/reports/*"))
        {
            return "active";
        }
        return "";
    }
    public static function isCreativeActive()
    {
        if(request()->is("publisher/creatives/*"))
        {
            return "active";
        }
        return "";
    }
    public static function isToolActive()
    {
        if(request()->is("publisher/tools/*"))
        {
            return "active";
        }
        return "";
    }
    public static function getMixNames($ids)
    {
        return Mix::select('name')->whereIn("id", $ids)->get()->pluck("name")->toArray();
    }
    public static function getMixIdz($names)
    {
        return Mix::select('id')->whereIn("name", $names)->get()->pluck("id")->toArray();
    }
    public static function getAdvertiserList()
    {
        $user = auth()->user();

        $advertisers = Advertiser::select([
                'advertisers.sid',
                'advertisers.name',
                'advertisers.deeplink_enabled'
            ])
            ->distinct()
            ->join('advertiser_publishers', 'advertisers.id', '=', 'advertiser_publishers.advertiser_id')
            ->where('advertiser_publishers.status', AdvertiserPublisher::STATUS_ACTIVE)
            ->where('advertiser_publishers.is_tracking_generate', AdvertiserPublisher::GENERATE_LINK_COMPLETE)
            ->where('advertiser_publishers.publisher_id', $user->publisher_id);

        return $advertisers->get()->toArray();

    }
}
