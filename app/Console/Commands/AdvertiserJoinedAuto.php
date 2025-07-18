<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Advertiser;
use App\Models\AdvertiserPublisher;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\Admin\PublisherManagement\Apply\MiscService;

class AdvertiserJoinedAuto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:advertiser-joined-auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
protected $service;
     public function __construct(MiscService $service)
    {
        parent::__construct(); // Call parent constructor
        $this->service = $service;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
         $user = User::where('email','affiliate@thriftdealz.com')->first();
         
           $advertiser = AdvertiserPublisher::select('id')->where('publisher_id', $user->publisher_id)->where('status','pending')->take(100)->get();
           $data = [];
     foreach($advertiser as $ad){
         $data [] = $ad->id;
     }
     print_r($data);
     $request =Request::create('/', 'POST', [
    'a_id' => $data,
    'status'=> 'joined',
], [], [], [
    'HTTP_X-Requested-With' => 'XMLHttpRequest',
]);

  $this->info($this->service->updateAdvertiserStatus($request));
    
    }
}
