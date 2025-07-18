<?php

namespace App\Services\Admin\PublisherManagement\Apply;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Sync\LinkJob;
use App\Models\AdvertiserPublisher;
use App\Models\FetchDailyData;
use App\Models\GenerateLink as GenerateLinkModel;
use App\Models\Tracking;
use App\Models\Website;
use App\Traits\GenerateLink;
use App\Traits\Notification\Advertiser\Approval;
use App\Traits\Notification\Advertiser\JoinedAdvertiserHold;
use App\Traits\Notification\Advertiser\JoinedAdvertiserReject;
use App\Traits\Notification\Advertiser\Reject;
use Illuminate\Http\Request;

class MiscService
{
    protected $message = null;
    
    use GenerateLink, Approval, Reject, JoinedAdvertiserHold, JoinedAdvertiserReject;

    public function updateAdvertiserStatus(Request $request)
    {
        $isAjax = $request->ajax();
        $status = false;
       
      try{
            AdvertiserPublisher::select('*', 'advertiser_sid as sid')
                ->whereIn('id', $request->a_id)
                ->chunk(5, function ($advertisers) use ($request) {
                    foreach ($advertisers as $advertiser) {
                        $trackURL = $longURL = $shortURL = null;
                        
                        $linkGenerate = AdvertiserPublisher::GENERATE_LINK_EMPTY;

                       if ($request['status'] == AdvertiserPublisher::STATUS_ACTIVE) {
                            $this->advertiserApprove($advertiser);
                            $generateURL = $this->generateLinkNShortLink($advertiser);
                       
                            $trackURL = $generateURL['track_url'];
                            $longURL = $generateURL['long_url'];
                            $shortURL = $generateURL['short_url'];
                            $linkGenerate = AdvertiserPublisher::GENERATE_LINK_IN_PROCESS;
                        } elseif ($request['status'] == AdvertiserPublisher::STATUS_HOLD && $advertiser->status == AdvertiserPublisher::STATUS_ACTIVE) {
                            $this->joinedAdvertiserHoldNotification($advertiser);
                        } elseif ($request['status'] == AdvertiserPublisher::STATUS_REJECTED) {
                            if ($advertiser->status == AdvertiserPublisher::STATUS_ACTIVE) {
                                $this->joinedAdvertiserRejectNotification($advertiser);
                            } else {
                                $this->rejectNotification($advertiser);
                            }
                        }

                        if (empty($this->message)) {
                            $advertiser->update([
                                'approver_id' => 1,
                                'reject_approve_reason' => $request->message ?? null,
                                'status' => $request->status,
                                'tracking_url' => $trackURL,
                                'tracking_url_long' => $longURL,
                                'tracking_url_short' => $shortURL,
                                'is_tracking_generate' => $linkGenerate,
                            ]);

                            Tracking::updateOrCreate(
                                [
                                    'advertiser_id' => $advertiser->advertiser_id,
                                    'website_id' => $advertiser->website_id,
                                    'publisher_id' => $advertiser->publisher_id,
                                    'sub_id' => null,
                                ],
                                [
                                    'tracking_url' => $trackURL,
                                    'tracking_url_short' => $shortURL,
                                    'tracking_url_long' => $longURL,
                                ]
                            );
                        }
                    }
                });

            if (empty($this->message)) {
                $this->message = "Apply Advertiser request successfully sent. Please wait, as it may take a few minutes to reflect the status change.";
                $status = true;
            }
          
        } catch (\Exception $exception) {
            $this->message = $exception->getMessage();
        }

        $type = $status ? 'success' : 'error';
        
        if ($isAjax) {
            if($request->hasSession()){
                  $request->session()->flash($type, $this->message);
            }
          
            
            return $this->message;
        }

        return redirect()->route('admin.approval.index', ['status' => $request['current_status']])->with($type, $this->message);
    }

    public function advertiserApprove($advertiser)
    {
        $advertiserCollection = $advertiser->advertiser;
        
        if (isset($advertiserCollection->id)) {
            $queue = Vars::LINK_GENERATE;
            
            GenerateLinkModel::updateOrCreate(
                [
                    'advertiser_id' => $advertiser->advertiser->id,
                    'publisher_id' => $advertiser->publisher_id,
                    'website_id' => $advertiser->website_id,
                    'sub_id' => null,
                ],
                [
                    'name' => 'MoveDataJob Link Job',
                    'path' => 'GenerateTrackingLinkJob',
                    'payload' => collect([
                        'advertiser' => $advertiserCollection,
                        'publisher_id' => $advertiser->publisher_id,
                        'website_id' => $advertiser->website_id,
                    ]),
                    'date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    'queue' => $queue,
                ]
            );
        }

        $this->approvalNotification($advertiser);
    }

    public function generateLinkNShortLink($advertiser)
    {
        $website = Website::select('wid')->where('id', $advertiser->website_id)->first();
        
        $trackURL = $advertiser->tracking_url ?? $this->generateLink($advertiser->advertiser_id, $advertiser->website_id);
        $shortURL = $advertiser->tracking_url_short ?? $this->generateShortLink();
        $longURL = $advertiser->tracking_url_long ?? $this->generateLongLink($advertiser->advertiser_sid, $website->wid, null);

        return [
            'track_url' => $trackURL,
            'long_url' => $longURL,
            'short_url' => $shortURL,
        ];
    }
}
