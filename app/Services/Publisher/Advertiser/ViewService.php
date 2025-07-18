<?php

namespace App\Services\Publisher\Advertiser;

use App\Models\Website;
use App\Models\Advertiser;
use App\Models\Transaction;
use Illuminate\Http\Request;


class ViewService extends BaseService
{
    public function init(Request $request, Advertiser $advertiser)
    {
        $user = $request->user();

        if (empty($user->active_website_id) || $user->active_website_status == Website::PENDING) {
            $this->websiteInactiveMsg($user);
        }

        // Fetch the advertiser and related data with the correct joins
       $advertiser = Advertiser::query()
    ->select([
        'advertisers.id',
        'advertisers.sid',
        'advertisers.name',
        'advertisers.deeplink_enabled',
        'advertisers.click_through_url',
        'advertisers.primary_regions',
        'advertisers.url',
        'advertisers.source',
        'advertisers.short_description',
        'advertisers.program_restrictions',
        'advertisers.promotional_methods',
        'advertisers.average_payment_time',
        'advertisers.logo',
        'advertisers.fetch_logo_url',
        'advertisers.is_fetchable_logo',
        'advertisers.commission',
        'advertisers.commission_type',
        'advertiser_publishers.status',
        'advertiser_publishers.locked_status',
        'advertiser_publishers.is_tracking_generate',
        'advertiser_publishers.tracking_url',
        'advertiser_publishers.tracking_url_long',
        'advertiser_publishers.tracking_url_short'
    ])
    ->leftJoin("advertiser_publishers", function ($join) use ($user) {
    $join->on("advertiser_publishers.advertiser_id", "=", "advertisers.id")
        //  ->where("advertiser_publishers.publisher_id", "=", '831c4afd-d1e8-4729-ba9c-84d979a6f881');
         ->where("advertiser_publishers.publisher_id", "=", $user->publisher_id);
})

    // ->where('advertisers.id', '26a88ab1-aa4a-4db3-9c68-82265a35c434') // Assuming $advertiser['id'] exists
    ->where('advertisers.id', $advertiser['id']) // Assuming $advertiser['id'] exists
    ->first();

        $title = "View Advertisers";

       seo()->title("{$title} — " . env("APP_NAME"));


        $headings = [
            'Advertisers',
            $title
        ];
        // return $advertiser;

        $transactions = Transaction::where('internal_advertiser_id', $advertiser['id'])->orderBy('transaction_date', 'DESC')->limit(6)->get();
        // return $transactions;
        return view("publisher.advertisers.view", compact('title', 'headings', 'advertiser', 'transactions'));
    }
}
