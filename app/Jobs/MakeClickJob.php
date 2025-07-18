<?php

namespace App\Jobs;

use App\Helper\Static\Methods;
use App\Models\TrackingClick;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class MakeClickJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data, $jobID, $isStatusChange;

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
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            $data = $this->data;

            // Use caching for repetitive operations
            $parsedDate = Carbon::parse($data['date']);
            $year = $parsedDate->format('Y');

            // Use firstOrNew to handle updates or inserts
            $trackingClick = TrackingClick::firstOrNew([
                'publisher_id' => $data['publisher_id'],
                'advertiser_id' => $data['advertiser_id'],
                'website_id' => $data['website_id'],
                'date' => $data['date'],
                'link_type' => $data['link_type']
            ]);

            $trackingClick->link_id = $data['id'];
            $trackingClick->created_year = $year;
            $trackingClick->total_clicks = ($trackingClick->total_clicks ?? 0) + $data['total_clicks'];
            $trackingClick->save();

            DB::table($data['detail_table'])
                ->where('publisher_id', $data['publisher_id'])
                ->where('advertiser_id', $data['advertiser_id'])
                ->where('website_id', $data['website_id'])
                ->whereDate('created_at', $data['date'])
                ->where('is_new', 1)
                ->update(['is_new' => 0]);

            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange, 'clicks');
        }
        catch (\Throwable $exception) {

            Methods::catchBodyFetchDaily("MAKE CLICK JOB", $exception, $this->jobID, 'clicks');

        }
    }
}
