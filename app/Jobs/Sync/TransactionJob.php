<?php

namespace App\Jobs\Sync;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\Advertiser;
use App\Models\Coupon;
use App\Models\CouponTracking;
use App\Models\DeeplinkTracking;
use App\Models\DelCouponTracking;
use App\Models\DelDeeplinkTracking;
use App\Models\DelTracking;
use App\Models\Tracking;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\User;
use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class TransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $source, $start_date, $end_date, $data, $jobID, $isStatusChange, $page;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->jobID = $data['job_id'];
        $this->isStatusChange = $data['is_status_change'];
        unset($data['job_id']);
        unset($data['is_status_change']);
        $this->source = $data['source'];
        $this->page = $data['page'];
        $this->start_date = $data['start_date'];
        $this->end_date = $data['end_date'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            $url = env("APP_SERVER_API_URL");
            $url = "{$url}api/sync-transactions?source={$this->source}&page={$this->page}";
            if($this->start_date && $this->end_date)
                $url = "{$url}&start_date={$this->start_date}&end_date={$this->end_date}";
            $response = Http::get($url);
            if($response->ok())
            {
                $transactions = $response->json();
                if(isset($transactions['data']))
                {
                    foreach ($transactions['data'] as $transaction)
                    {
                        $data = Transaction::where("transaction_id", $transaction['transaction_id'])->where('source', $transaction['source'])->first();

                        $setting = Setting::select('value')->where("key", "publisher_commission_settings")->where('key_2', 'publisher_id')->where('value_2', $transaction['publisher_id'] ?? null)->first();

                        if(empty($setting))
                        {
                            $setting = Setting::select('value')->where("key", "default_commission")->first();
                        }
                        $transaction['before_percentage_commission'] = $transaction['commission_amount'];
                        if($transaction['source'] == 'Quk'){
                            $transaction['commission_amount'] = $transaction['commission_amount'] * 90 / 100;
                        }else{
                            $transaction['commission_amount'] = $transaction['commission_amount'] * $setting->value / 100;
                        }
                        

                        if(isset($data->id))
                        {

                            $advertiser = Advertiser::where("advertiser_id", $data->advertiser_id)->where("source", $data->source)->first();

                            $transaction['external_advertiser_id'] = $advertiser->sid ?? 0;

                            if(strtolower($transaction['commission_status']) == Transaction::STATUS_APPROVED_STALLED)
                                $transaction['commission_status'] = Transaction::STATUS_PENDING;

                            if($data->payment_status != Transaction::PAYMENT_STATUS_NOT_APPROVED)
                            {
                                unset($transaction['commission_status']);
                            }

                            unset($transaction['payment_status']);
                            unset($transaction['website_id']);
                            unset($transaction['publisher_id']);

                            if((empty($data->website_id) || empty($data->publisher_id)) && isset($transaction['provider_response']) && $transaction['provider_response'])
                            {
                                $providerResponse = $this->providerResponseGetData($transaction['source'], $transaction['provider_response']);
                                $transaction['website_id'] = $providerResponse['website_id'] ?? null;
                                $transaction['publisher_id'] = $providerResponse['publisher_id'] ?? null;
                                if(isset($providerResponse['sub_id']) && $providerResponse['sub_id'] && empty($transaction['sub_id']))
                                    $transaction['sub_id'] = $providerResponse['sub_id'];
                            }

                            if($data->commission_amount != $transaction['commission_amount'])
                            {
                                $transaction['last_commission'] = $data->commission_amount;
                                $transaction['last_sales_amount'] = $data->sale_amount;
                            }
                            if($transaction['paid_to_publisher'] || $data->paid_to_publisher || $data->payment_status > 0)
                            {
                                unset($transaction['received_commission_amount']);
                                unset($transaction['received_sale_amount']);
                                unset($transaction['commission_amount']);
                                unset($transaction['sale_amount']);
                                unset($transaction['payment_status']);
                                $transaction['paid_to_publisher'] = $data->paid_to_publisher;
                            }
                            $data->update($transaction);
                        }
                        else
                        {
                            if((empty($transaction['publisher_id']) || empty($transaction['website_id'])) && isset($transaction['provider_response']) && $transaction['provider_response'])
                            {
                                $providerResponse = $this->providerResponseGetData($transaction['source'], $transaction['provider_response']);
                                $transaction['website_id'] = $providerResponse['website_id'] ?? null;
                                $transaction['publisher_id'] = $providerResponse['publisher_id'] ?? null;
                                if(isset($providerResponse['sub_id']) && $providerResponse['sub_id'] && empty($transaction['sub_id']))
                                    $transaction['sub_id'] = $providerResponse['sub_id'];
                            }

                            Transaction::create($transaction);
                        }
                    }
                }
            }

            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);

        } catch (\Throwable $exception) {

            Methods::catchBodyFetchDaily("SYNC TRANSACTIONS JOB", $exception, $this->jobID);

        }
    }

    private function providerResponseGetData($source, $response)
    {
        $response = unserialize($response);
        switch ($source)
        {
            case Vars::ADMITAD:
                return $this->admitad($response);

            case Vars::AWIN:
                return $this->awin($response);

            case Vars::IMPACT_RADIUS:
                return $this->impact($response);

            case Vars::LINKCONNECTOR:
                return $this->linkconnector($response);

            case Vars::PARTNERIZE:
                return $this->partnerize($response);

            case Vars::PEPPERJAM:
                return $this->pepperjam($response);

            case Vars::RAKUTEN:
                return $this->rakuten($response);

            case Vars::TRADEDOUBLER:
                return $this->tradedoubler($response);

            default:
                break;
        }
    }

    private function admitad($response)
    {
        $advertiser = Advertiser::select('id')->where("advertiser_id", $response['advcampaign_id'])->where('source', Vars::ADMITAD)->first();

        $publisherID = $websiteID = $subID = null;
        if($response['subid'] && $response['subid1'] && $response['subid2'] && $response['subid3'])
        {
            $publisherID = $response['subid1'];
            $websiteID = $response['subid2'];
            $subID = $response['subid3'];
        }
        elseif($response['subid'] && $response['subid1'] && $response['subid2'] && empty($response['subid3']))
        {
            $publisherID = $response['subid1'];
            $websiteID = $response['subid2'];
        }
        elseif($response['subid'] && empty($response['subid1']) && empty($response['subid2']) && empty($response['subid3']))
        {
            $tracking = $this->findIDsInTrackingLinksWithID($advertiser->id, $response['subid']);
            if($tracking['publisher_id'] && $tracking['website_id'])
            {
                $websiteID = $tracking['website_id'];
                $publisherID = $tracking['publisher_id'];
                $subID = $tracking['sub_id'];
            }
            else
            {
                $tracking = $this->findIDsInDeepLinksWithID($advertiser->id, $response['subid']);
                if($tracking['publisher_id'] && $tracking['website_id'])
                {
                    $websiteID = $tracking['website_id'];
                    $publisherID = $tracking['publisher_id'];
                    $subID = $tracking['sub_id'];
                }
                else {

                    $tracking = $this->findIDsInCouponLinksWithID($advertiser->id, $response['subid']);
                    if($tracking['publisher_id'] && $tracking['website_id'])
                    {
                        $websiteID = $tracking['website_id'];
                        $publisherID = $tracking['publisher_id'];
                        $subID = $tracking['sub_id'];
                    }
                    else
                    {
                        \Log::warning("WEBSITE ID NOT FOUND");
                        \Log::warning(json_encode($response));
                    }
                }
            }
        }
        elseif(empty($response['subid']) && empty($response['subid1']) && empty($response['subid2']) && empty($response['subid3']))
        {
            $website = Website::where(function($query) use ($response) {
                $query->where("url", $response['click_user_referer'])
                    ->orWhere("name", "LIKE", "%{$response['website_name']}%");
            })
                ->first();

            if ($website) {
                $tracking = Tracking::where("advertiser_id", $advertiser->id)
                    ->where("website_id", $website->id)
                    ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
                    ->first();

                if ($tracking) {
                    $websiteID = $tracking->website_id;
                    $publisherID = $tracking->publisher_id;
                    $subID = $tracking->sub_id;
                } else {
                    $deepTracking = DeeplinkTracking::where("advertiser_id", $advertiser->id)
                        ->where("website_id", $website->id)
                        ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
                        ->first();

                    if ($deepTracking) {
                        $websiteID = $deepTracking->website_id;
                        $publisherID = $deepTracking->publisher_id;
                        $subID = $deepTracking->sub_id;
                    } else {
                        $couponTracking = CouponTracking::where("advertiser_id", $advertiser->id)
                            ->where("website_id", $website->id)
                            ->select(['id', 'publisher_id', 'website_id'])
                            ->first();

                        if ($couponTracking) {
                            $websiteID = $couponTracking->website_id;
                            $publisherID = $couponTracking->publisher_id;
                            $subID = null; // Assuming $subID needs to be set even when not found in tracking or deeplink
                        } else {

                            $delTracking = DelTracking::where("advertiser_id", $advertiser->id)
                                ->where("website_id", $website->id)
                                ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
                                ->first();

                            if ($delTracking) {
                                $websiteID = $delTracking->website_id;
                                $publisherID = $delTracking->publisher_id;
                                $subID = $delTracking->sub_id;
                            } else {

                                $delDeepTracking = DelDeeplinkTracking::where("advertiser_id", $advertiser->id)
                                    ->where("website_id", $website->id)
                                    ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
                                    ->first();

                                if ($delDeepTracking) {
                                    $websiteID = $delDeepTracking->website_id;
                                    $publisherID = $delDeepTracking->publisher_id;
                                    $subID = $delDeepTracking->sub_id;
                                } else {


                                    $delCouponTracking = DelCouponTracking::where("advertiser_id", $advertiser->id)
                                        ->where("website_id", $website->id)
                                        ->select(['id', 'publisher_id', 'website_id'])
                                        ->first();

                                    if ($delCouponTracking) {
                                        $websiteID = $delCouponTracking->website_id;
                                        $publisherID = $delCouponTracking->publisher_id;
                                        $subID = null; // Assuming $subID needs to be set even when not found in tracking or deeplink
                                    } else {

                                        $publisher = $website->users->first();

                                        $websiteID = $website->id;
                                        $publisherID = $publisher->id;
                                        $subID = null; // Assuming $subID needs to be set even when no tracking data is found

                                    }

                                }

                            }

                        }
                    }
                }
            }

        }

        return [
            'publisher_id'  => $publisherID,
            'website_id'    => $websiteID,
            'sub_id'        => $subID
        ];
    }

    private function awin($response)
    {
        $publisherID = $websiteID = $subID = null;
        if(isset($response['clickRefs']['clickRef']) && isset($response['clickRefs']['clickRef2']) && isset($response['clickRefs']['clickRef3']))
        {
            $publisherID = $response['clickRefs']['clickRef'];
            $websiteID = $response['clickRefs']['clickRef2'];
            $subID = $response['clickRefs']['clickRef3'];
        }
        elseif(isset($response['clickRefs']['clickRef']) && isset($response['clickRefs']['clickRef2']))
        {
            $publisherID = $response['clickRefs']['clickRef'];
            $websiteID = $response['clickRefs']['clickRef2'];
        }
        elseif(isset($response['clickRefs']['clickRef']))
        {
            $publisherID = $response['clickRefs']['clickRef'];
            $user = User::where('id', $publisherID)->first();
            $websiteID = $user->websites[0]->id ?? null;
        }
        elseif (isset($response['publisherUrl']))
        {
            $url = $response['publisherUrl'];
            $url = parse_url($url, PHP_URL_HOST);
            $website = Website::with('users:id')->where('url', 'LIKE', "%$url%")->first();
            $publisher = $website?->users->first();
            $websiteID = $website->id ?? null;
            $publisherID = $publisher->id ?? null;
        }

        return [
            'publisher_id'  => $publisherID,
            'website_id'    => $websiteID,
            'sub_id'        => $websiteID
        ];
    }

    private function impact($response)
    {
        $publisherID = $websiteID = null;
        if(isset($response['SubId1']) && isset($response['SubId2']))
        {
            $publisherID = $response['SubId1'];
            $websiteID = $response['SubId2'];
        } elseif (isset($response['ReferringDomain']))
        {
            $url = $response['ReferringDomain'];
            $url = parse_url($url, PHP_URL_HOST);
            $website = Website::with('users:id')->where('url', 'LIKE', "%$url%")->first();
            $publisher = $website->users->first();
            $websiteID = $website->id;
            $publisherID = $publisher->id;
        }

        return [
            'publisher_id'  => $publisherID,
            'website_id'    => $websiteID,
        ];
    }

    private function linkconnector($response)
    {
        $advertiser = Advertiser::select('id')->where("advertiser_id", $response['Merchant ID'])->where('source', Vars::LINKCONNECTOR)->first();

        $publisherID = $websiteID = $subID = null;
        if(isset($response['ATID']) && $response['ATID'])
        {
            $u1 = $response['ATID'];

            $tracking = $this->findIDsInTrackingLinksWithID($advertiser->id, $u1);
            if($tracking['publisher_id'] && $tracking['website_id'])
            {
                $websiteID = $tracking['website_id'];
                $publisherID = $tracking['publisher_id'];
                $subID = $tracking['sub_id'];
            }
            else
            {
                $tracking = $this->findIDsInDeepLinksWithID($advertiser->id, $u1);
                if($tracking['publisher_id'] && $tracking['website_id'])
                {
                    $websiteID = $tracking['website_id'];
                    $publisherID = $tracking['publisher_id'];
                    $subID = $tracking['sub_id'];
                }
                else {

                    $tracking = $this->findIDsInCouponLinksWithID($advertiser->id, $u1);
                    if($tracking['publisher_id'] && $tracking['website_id'])
                    {
                        $websiteID = $tracking['website_id'];
                        $publisherID = $tracking['publisher_id'];
                        $subID = $tracking['sub_id'];
                    }
                    else
                    {
                        \Log::warning("WEBSITE ID NOT FOUND");
                        \Log::warning(json_encode($response));
                    }
                }
            }
        }

        return [
            'publisher_id'  => $publisherID,
            'website_id'    => $websiteID,
            'sub_id'        => $subID
        ];
    }

    private function partnerize($response)
    {
        $publisherID = $websiteID = $subID = null;
        if(isset($response['meta_data']['subid1']))
        {
            $metaData = $response['meta_data'];
        }
        else
        {
            $metaData = $response['click']['meta_data'];
        }
        if(!isset($metaData['subid1']) || !isset($metaData['subid2']))
        {
            info(json_encode($response));
            return;
        }
        if($metaData['subid1'])
        {
            $publisherID = $metaData['subid1'];
        }
        if($metaData['subid2'])
        {
            $websiteID = $metaData['subid2'];
        }
        if(isset($metaData['subid3']))
        {
            $subID = $metaData['subid3'];
        }

        return [
            'publisher_id'  => $publisherID,
            'website_id'    => $websiteID,
            'sub_id'        => $subID
        ];
    }

    private function pepperjam($response)
    {
        $advertiser = Advertiser::select('id')->where("advertiser_id", $response['program_id'])->where('source', Vars::PEPPERJAM)->first();

        $publisherID = $websiteID = $subID = null;
        if(isset($response['sid']) && $response['sid'])
        {
            $u1 = $response['sid'];

            $tracking = $this->findIDsInTrackingLinksWithID($advertiser->id, $u1);
            if($tracking['publisher_id'] && $tracking['website_id'])
            {
                $websiteID = $tracking['website_id'];
                $publisherID = $tracking['publisher_id'];
                $subID = $tracking['sub_id'];
            }
            else
            {
                $tracking = $this->findIDsInDeepLinksWithID($advertiser->id, $u1);
                if($tracking['publisher_id'] && $tracking['website_id'])
                {
                    $websiteID = $tracking['website_id'];
                    $publisherID = $tracking['publisher_id'];
                    $subID = $tracking['sub_id'];
                }
                else {

                    $tracking = $this->findIDsInCouponLinksWithID($advertiser->id, $u1);
                    if($tracking['publisher_id'] && $tracking['website_id'])
                    {
                        $websiteID = $tracking['website_id'];
                        $publisherID = $tracking['publisher_id'];
                        $subID = $tracking['sub_id'];
                    }
                    else
                    {
                        \Log::warning("WEBSITE ID NOT FOUND");
                        \Log::warning(json_encode($response));
                    }
                }
            }
        }

        return [
            'publisher_id'  => $publisherID,
            'website_id'    => $websiteID,
            'sub_id'        => $subID
        ];

    }

    private function rakuten($response)
    {
        $advertiser = Advertiser::select('id')->where("advertiser_id", $response['advertiser_id'])->where('source', Vars::RAKUTEN)->first();

        $publisherID = $websiteID = $subID = null;
        if(isset($response['u1']) && $response['u1'])
        {
            $u1 = $response['u1'];

            $tracking = $this->findIDsInTrackingLinksWithID($advertiser->id, $u1);
            if($tracking['publisher_id'] && $tracking['website_id'])
            {
                $websiteID = $tracking['website_id'];
                $publisherID = $tracking['publisher_id'];
                $subID = $tracking['sub_id'];
            }
            else
            {
                $tracking = $this->findIDsInDeepLinksWithID($advertiser->id, $u1);
                if($tracking['publisher_id'] && $tracking['website_id'])
                {
                    $websiteID = $tracking['website_id'];
                    $publisherID = $tracking['publisher_id'];
                    $subID = $tracking['sub_id'];
                }
                else {

                    $tracking = $this->findIDsInCouponLinksWithID($advertiser->id, $u1);
                    if($tracking['publisher_id'] && $tracking['website_id'])
                    {
                        $websiteID = $tracking['website_id'];
                        $publisherID = $tracking['publisher_id'];
                        $subID = $tracking['sub_id'];
                    }
                    else
                    {
                        $website = Website::where('id', $u1)->first();
                        if($website)
                        {
                            $publisherID = $website->users[0]['id'] ?? null;
                            $websiteID = $u1;
                        }
                        else
                        {
                            \Log::warning("WEBSITE ID NOT FOUND");
                            \Log::warning(json_encode($response));
                        }
                    }
                }
            }
        }

        return [
            'publisher_id'  => $publisherID,
            'website_id'    => $websiteID,
            'sub_id'        => $subID
        ];

    }

    private function tradedoubler($response)
    {
        $advertiser = Advertiser::select('id')->where("advertiser_id", $response['programId'])->where('source', Vars::TRADEDOUBLER)->first();

        $publisherID = $websiteID = $subID = null;
        if(isset($response['epi']) && $response['epi'])
        {
            $u1 = $response['epi'];

            $tracking = $this->findIDsInTrackingLinksWithID($advertiser->id, $u1);
            if($tracking['publisher_id'] && $tracking['website_id'])
            {
                $websiteID = $tracking['website_id'];
                $publisherID = $tracking['publisher_id'];
                $subID = $tracking['sub_id'];
            }
            else
            {
                $tracking = $this->findIDsInDeepLinksWithID($advertiser->id, $u1);
                if($tracking['publisher_id'] && $tracking['website_id'])
                {
                    $websiteID = $tracking['website_id'];
                    $publisherID = $tracking['publisher_id'];
                    $subID = $tracking['sub_id'];
                }
                else {

                    $tracking = $this->findIDsInCouponLinksWithID($advertiser->id, $u1);
                    if($tracking['publisher_id'] && $tracking['website_id'])
                    {
                        $websiteID = $tracking['website_id'];
                        $publisherID = $tracking['publisher_id'];
                        $subID = $tracking['sub_id'];
                    }
                    else
                    {
                        $website = Website::where('id', $u1)->first();
                        if($website)
                        {
                            $publisherID = $website->users[0]['id'] ?? null;
                            $websiteID = $u1;
                        }
                        else
                        {
                            \Log::warning("WEBSITE ID NOT FOUND");
                            \Log::warning(json_encode($response));
                        }
                    }
                }
            }
        }

        return [
            'publisher_id'  => $publisherID,
            'website_id'    => $websiteID,
            'sub_id'        => $subID
        ];

    }

    private function findIDsInTrackingLinksWithID($advertiserID, $id)
    {
        $publisherID = $websiteID = $subID = null;

        $tracking = Tracking::where("advertiser_id", $advertiserID)
            ->where("website_id", $id)
            ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
            ->first();

        if ($tracking) {
            $websiteID = $tracking->website_id;
            $publisherID = $tracking->publisher_id;
            $subID = $tracking->sub_id;
        }
        else {

            $tracking = Tracking::where("advertiser_id", $advertiserID)
                ->where("sub_id", $id)
                ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
                ->first();

            if ($tracking) {
                $websiteID = $tracking->website_id;
                $publisherID = $tracking->publisher_id;
                $subID = $tracking->sub_id;
            }
            else {

                $tracking = DelTracking::where("advertiser_id", $advertiserID)
                    ->where("sub_id", $id)
                    ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
                    ->first();

                if ($tracking) {
                    $websiteID = $tracking->website_id;
                    $publisherID = $tracking->publisher_id;
                    $subID = $tracking->sub_id;
                }
                else {
                    $tracking = DelTracking::where("advertiser_id", $advertiserID)
                        ->where("website_id", $id)
                        ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
                        ->first();

                    if ($tracking) {
                        $websiteID = $tracking->website_id;
                        $publisherID = $tracking->publisher_id;
                        $subID = $tracking->sub_id;
                    }
                }

            }
        }

        return [
            'publisher_id' => $publisherID,
            'website_id' => $websiteID,
            'sub_id' => $subID,
        ];
    }

    private function findIDsInTrackingLinksWithSubIDNWebsiteID($advertiserID, $websiteID, $subID)
    {
        $publisherID = $websiteID = $subID = null;

        $tracking = Tracking::where("advertiser_id", $advertiserID)
            ->where("website_id", $websiteID)
            ->where("sub_id", $subID)
            ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
            ->first();

        if ($tracking) {
            $websiteID = $tracking->website_id;
            $publisherID = $tracking->publisher_id;
            $subID = $tracking->sub_id;
        }
        else {

            $tracking = Tracking::where("advertiser_id", $advertiserID)
                ->where("website_id", $websiteID)
                ->where("sub_id", $subID)
                ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
                ->first();

            if ($tracking) {
                $websiteID = $tracking->website_id;
                $publisherID = $tracking->publisher_id;
                $subID = $tracking->sub_id;
            }
            else {

                $tracking = DelTracking::where("advertiser_id", $advertiserID)
                    ->where("website_id", $websiteID)
                    ->where("sub_id", $subID)
                    ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
                    ->first();

                if ($tracking) {
                    $websiteID = $tracking->website_id;
                    $publisherID = $tracking->publisher_id;
                    $subID = $tracking->sub_id;
                }
                else {
                    $tracking = DelTracking::where("advertiser_id", $advertiserID)
                        ->where("website_id", $websiteID)
                        ->where("sub_id", $subID)
                        ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
                        ->first();

                    if ($tracking) {
                        $websiteID = $tracking->website_id;
                        $publisherID = $tracking->publisher_id;
                        $subID = $tracking->sub_id;
                    }
                }

            }
        }

        return [
            'publisher_id' => $publisherID,
            'website_id' => $websiteID,
            'sub_id' => $subID,
        ];
    }

    private function findIDsInDeepLinksWithID($advertiserID, $id)
    {
        $publisherID = $websiteID = $subID = null;

        $tracking = DeeplinkTracking::where("advertiser_id", $advertiserID)
            ->where("website_id", $id)
            ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
            ->first();

        if ($tracking) {
            $websiteID = $tracking->website_id;
            $publisherID = $tracking->publisher_id;
            $subID = $tracking->sub_id;
        }
        else {

            $tracking = DeeplinkTracking::where("advertiser_id", $advertiserID)
                ->where("sub_id", $id)
                ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
                ->first();

            if ($tracking) {
                $websiteID = $tracking->website_id;
                $publisherID = $tracking->publisher_id;
                $subID = $tracking->sub_id;
            }
            else {

                $tracking = DelDeeplinkTracking::where("advertiser_id", $advertiserID)
                    ->where("sub_id", $id)
                    ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
                    ->first();

                if ($tracking) {
                    $websiteID = $tracking->website_id;
                    $publisherID = $tracking->publisher_id;
                    $subID = $tracking->sub_id;
                }
                else {
                    $tracking = DelDeeplinkTracking::where("advertiser_id", $advertiserID)
                        ->where("website_id", $id)
                        ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
                        ->first();

                    if ($tracking) {
                        $websiteID = $tracking->website_id;
                        $publisherID = $tracking->publisher_id;
                        $subID = $tracking->sub_id;
                    }
                }

            }
        }

        return [
            'publisher_id' => $publisherID,
            'website_id' => $websiteID,
            'sub_id' => $subID,
        ];
    }

    private function findIDsInCouponLinksWithID($advertiserID, $id)
    {
        $publisherID = $websiteID = $subID = null;

        $tracking = CouponTracking::where("advertiser_id", $advertiserID)
            ->where("website_id", $id)
            ->select(['id', 'publisher_id', 'website_id'])
            ->first();

        if ($tracking) {
            $websiteID = $tracking->website_id;
            $publisherID = $tracking->publisher_id;
        }
        else {

            $tracking = DelCouponTracking::where("advertiser_id", $advertiserID)
                ->where("website_id", $id)
                ->select(['id', 'publisher_id', 'website_id', 'sub_id'])
                ->first();

            if ($tracking) {
                $websiteID = $tracking->website_id;
                $publisherID = $tracking->publisher_id;
                $subID = $tracking->sub_id;
            }

        }

        return [
            'publisher_id' => $publisherID,
            'website_id' => $websiteID,
            'sub_id' => $subID,
        ];
    }
}
