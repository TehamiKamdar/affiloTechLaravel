<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Advertiser;
use App\Models\AdvertiserPublisher;
use App\Models\Tracking;
use App\Helper\LinkGenerate;

class linkGenerateCorrect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:link-generate-correct';

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
       $publisher = AdvertiserPublisher::where('status','joined')->get();
       foreach($publisher as $activeAdvertiser){
             $link = new LinkGenerate();
             $as = Advertiser::find($activeAdvertiser->advertiser_id);
             if($as){
                  $tracking = Tracking::where('advertiser_id',$activeAdvertiser->advertiser_id)->where('publisher_id',$activeAdvertiser->publisher_id)->where('website_id',$activeAdvertiser->website_id)->first();
             if($tracking){
                 $sub = $tracking->sub_id;
             }else{
                 $sub = null;
             }
            
                $clickLink = $link->generate($as , $activeAdvertiser->publisher_id, $activeAdvertiser->website_id, $sub);
                 if(!empty($clickLink)){
                      if($tracking){
                $tracking->update([
                        'click_through_url' => $clickLink
                    ]);
             }
             
             $activeAdvertiser->update([
                        'click_through_url' => $clickLink
                    ]);
                 }
             }
            
                    
                
       }
     
    }
}
