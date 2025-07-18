<?php

namespace App\Jobs\Sync;

use App\Helper\Static\Methods;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class PaymentJob implements ShouldQueue
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
            $url = "{$url}api/sync-payments?source={$this->source}&page={$this->page}&is_update=1";
            $response = Http::get($url);
            if ($response->ok()) {
                $transactions = $response->json();
                foreach ($transactions['data'] as $transaction) {
                    $data = Transaction::where("transaction_id", $transaction['transaction_id'])->where('source', $transaction['source'])->first();

                    if (isset($data->id)) {
                        $data->update([
                            'paid_to_publisher' => $transaction['paid_to_publisher']
                        ]);
                    }
                }
            }

            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);

        } catch (\Throwable $exception) {

            Methods::catchBodyFetchDaily("SYNC PAYMENT JOB", $exception, $this->jobID);

        }
    }
}
