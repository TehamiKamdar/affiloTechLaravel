<?php

namespace App\Jobs\Move;

use App\Helper\Static\Methods;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class MoveDataJob implements ShouldQueue
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

            $sourceTable = $this->data['sourceTable'];
            $destTable = $this->data['destTable'];
            $columns = $this->data['columns'];
            $chunkSize = $this->data['chunkSize'];

            if(isset($this->data['rejectedAdvertisers']))
            {
                $rejectedAdvertisers = $this->data['rejectedAdvertisers'];

                DB::table($sourceTable)
                    ->whereIn('advertiser_id', $rejectedAdvertisers)
                    ->chunkById($chunkSize, function ($records) use ($sourceTable, $destTable, $columns) {
                        $destRecords = [];
                        foreach ($records as $record) {
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
                    });
            }
            elseif (isset($this->data['rejectedPublishers']))
            {
                $rejectedPublishers = $this->data['rejectedPublishers'];

                DB::table($sourceTable)
                    ->whereIn('publisher_id', $rejectedPublishers)
                    ->chunkById($chunkSize, function ($records) use ($sourceTable, $destTable, $columns) {
                        $destRecords = [];
                        foreach ($records as $record) {
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
                    });
            }
            elseif (isset($this->data['rejectedWebsites']))
            {
                $rejectedWebsites = $this->data['rejectedWebsites'];

                DB::table($sourceTable)
                    ->whereIn('website_id', $rejectedWebsites)
                    ->chunkById($chunkSize, function ($records) use ($sourceTable, $destTable, $columns) {
                        $destRecords = [];
                        foreach ($records as $record) {
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
                    });
            }

            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);

        } catch (\Throwable $exception) {

            Methods::catchBodyFetchDaily("MOVE DATA JOB", $exception, $this->jobID);

        }

    }
}
