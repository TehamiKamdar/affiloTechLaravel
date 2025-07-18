<?php

namespace App\Console\Commands\Global;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\DeeplinkGenerateJob;
use App\Jobs\GenerateTrackingLinkJob;
use App\Jobs\GenerateTrackingLinkWithSubIDJob;
use App\Models\AdvertiserPublisher;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use App\Models\GenerateLink;
use Illuminate\Console\Command;

class LinkGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Tracking / Deep Link.';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $jobCheck = GenerateLink::select('id')->where('is_processing', Vars::JOB_IN_PROCESS)->count();

        if($jobCheck == 0)
        {
            $queue = Vars::LINK_GENERATE;
            $this->prepareFuncToGenerateAdvertiserApplyLink();
            $this->prepareFuncToGenerateDeepLink($queue);
            $this->prepareFuncToGenerateTrackingLink($queue, "GenerateTrackingLinkWithSubIDJob");
            $this->prepareFuncToGenerateTrackingLink($queue);
        }
    }

    private function prepareFuncToGenerateAdvertiserApplyLink(): void
    {
        AdvertiserPublisher::where("status", AdvertiserPublisher::STATUS_ACTIVE)
    ->whereNull("click_through_url")
    ->chunk(300, function ($advertiserApplysChunk) {
        // Fetch all advertiser IDs from the chunk
        $advertiserIds = $advertiserApplysChunk->pluck('advertiser_id')->toArray();

        // Retrieve advertisers in bulk to reduce queries
        $advertisers = Advertiser::whereIn('id', $advertiserIds)
                                 ->pluck('click_through_url', 'id');

        foreach ($advertiserApplysChunk as $advertiserApply) {
            \Log::channel('broadcast')->info("Processing AdvertiserApply ID: {$advertiserApply->id}, Advertiser ID: {$advertiserApply->advertiser_id}");

            if (isset($advertisers[$advertiserApply->advertiser_id])) {
                $clickThroughUrl = $advertisers[$advertiserApply->advertiser_id];

                $advertiserApply->update([
                    'click_through_url' => $clickThroughUrl,
                    'is_tracking_generate' => AdvertiserPublisher::GENERATE_LINK_COMPLETE
                ]);

                \Log::channel('broadcast')->info("Updated AdvertiserApply ID: {$advertiserApply->id} with URL: $clickThroughUrl");
            } else {
                \Log::channel('broadcast')->info("Advertiser not found, skipping AdvertiserApply ID: {$advertiserApply->id}, Advertiser ID: {$advertiserApply->advertiser_id}");
            }
        }
    });
    }

    private function prepareFuncToGenerateTrackingLink($queue, $path = "GenerateTrackingLinkJob"): void
    {
     
       $okay = $this->jobDispatch($path, $queue, true, 50);
       
    }

    private function prepareFuncToGenerateDeepLink($queue): void
    {
        $path = "DeeplinkGenerateJob";
        $this->jobDispatch($path, $queue, true, 50);
    }

    public function jobDispatch($path, $queue, $isStatusChange, $take): void
    {
        $jobs = GenerateLink::select([
                                'id', 'payload', 'queue', 'path', 'publisher_id', 'website_id', 'sub_id', 'is_processing'
                            ])
                            ->where("date", "<=", now()->format(Vars::CUSTOM_DATE_FORMAT_2))
                            ->where("status", Vars::JOB_ACTIVE)
                            ->where("queue", $queue)
                            ->where("path", $path)
                            ->take($take)
                            ->get();

        foreach ($jobs as $job)
        {

            try {

                if(isset($job->id))
                {
//                    $job->update([
//                        'is_processing' => Vars::JOB_IN_PROCESS
//                    ]);
                    $payload = json_decode($job->payload);
                    $queue = $job->queue;
                    switch ($job->path)
                    {
                        case "GenerateTrackingLinkJob":
                            if(isset($payload->advertiser))
                            {
                                GenerateTrackingLinkJob::dispatch($job->id, $payload->advertiser, $job->publisher_id, $job->website_id, $job->sub_id, $isStatusChange)->onQueue($queue);
                            }
                            else
                            {
//                                Methods::customError("LinkGenerateCommand (Generate Tracking Link Job)", "PAYLOAD ADVERTISER EMPTY");
//                                Methods::customError("LinkGenerateCommand (Generate Tracking Link Job)", $payload);

                                GenerateLink::where("id", $job->id)->update([
                                    'status' => Vars::JOB_STATUS_COMPLETE,
                                    'is_processing' => Vars::JOB_ACTIVE
                                ]);
                            }
                            break;

                        case "DeeplinkGenerateJob":
                            $payload = json_decode($job->payload, true);
                            $payload['job_id'] = $job->id;
                            $payload['sub_id'] = $job->sub_id;
                            DeeplinkGenerateJob::dispatch($payload, $isStatusChange)->onQueue($queue);
                            break;

                        case "GenerateTrackingLinkWithSubIDJob":
                            if(isset($payload->advertiser))
                            {
                                GenerateTrackingLinkWithSubIDJob::dispatch($job->id, $payload->advertiser, $job->publisher_id, $job->website_id, $job->sub_id, $isStatusChange)->onQueue($queue);
                            }
                            else
                            {
//                                Methods::customError("LinkGenerateCommand (Generate Tracking Link Job)", "PAYLOAD ADVERTISER EMPTY");
//                                Methods::customError("LinkGenerateCommand (Generate Tracking Link Job)", $payload);

                                GenerateLink::where("id", $job->id)->update([
                                    'status' => Vars::JOB_STATUS_COMPLETE,
                                    'is_processing' => Vars::JOB_ACTIVE
                                ]);

                                if($isStatusChange)
                                {
                                    GenerateLink::where('status', Vars::JOB_STATUS_IN_PROCESS)->update([
                                        'date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2)
                                    ]);
                                }
                            }
                            break;

                        default:
                            break;
                    }
                }

            } catch (\Exception $exception)
            {
                $job->update([
                    'is_processing' => Vars::JOB_ERROR
                ]);
            }
        }
    }
}
