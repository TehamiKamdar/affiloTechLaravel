<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Advertiser;
use App\Models\AdvertiserPublisher;
use App\Models\Country;
use App\Models\Mix;
use App\Models\User;
use App\Models\Website;

class AutoJoined extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-joined';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email','affiliate@thriftdealz.com')->first();
         $query = Advertiser::query()
        ->select([
            'advertisers.id',
            'advertisers.sid',
            'advertisers.name',
            'advertisers.deeplink_enabled',
            'advertisers.click_through_url',
            'advertisers.average_payment_time',
            'advertisers.fetch_logo_url',
        'advertisers.is_fetchable_logo',
            'advertisers.primary_regions',
            'advertisers.supported_regions',
            'advertisers.logo',
            'advertisers.source',
            'advertisers.commission as commission',
            'advertisers.commission_type',
        ])
        ->leftJoin(env("RDS_DB_NAME") . ".advertiser_publishers", function ($join) use ($user) {
            $join->on("advertiser_publishers.advertiser_id", "=", "advertisers.id")
                ->where("advertiser_publishers.publisher_id", "=", $user->publisher_id)
                ->where("advertiser_publishers.website_id", "=", $user->active_website_id);
        })
        ->whereNull('advertiser_publishers.advertiser_id')->where('advertisers.is_active',1)->get() ;
        
        foreach($query as $q){
            $advertiserSource = $q->source;
            $advertiserID = $q->sid;
            $advertiser = Advertiser::where('sid',$advertiserID)->first();
       $criteria = [
    'advertiser_id'   => $advertiser->id,
    'source'          => $advertiserSource,
    'publisher_id'    => $user->publisher_id,
    'website_id'      => $user->active_website_id,
    'advertiser_sid'  => $advertiser->sid,
];

// Try to find existing record
$publisher = AdvertiserPublisher::where($criteria)->where('status','pending')->first();

$data = [
    'applied_at' => now(),
    'status'     => 'pending',
    'click_through_url' =>$advertiser->click_through_url
];

if ($publisher) {
    // Update existing
    $publisher->update($data);
} else {
    // Create new
    $publisher = AdvertiserPublisher::create(array_merge($criteria, $data));
}


            }
    }
}
