<?php
namespace App\Jobs\Export;

use App\Helper\Methods;
use App\Models\ExportFiles;
use App\Models\User;
use App\Services\Publisher\AdvertiserService;
use App\Services\Publisher\Reporting\Transaction\BaseService;
use App\Services\Publisher\TransactionService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class TransactionExport implements ShouldQueue
{
    use Queueable;

    protected $data, $jobID, $isStatusChange;

    public function __construct($data)
    {
        $this->jobID = $data["job_id"];
        $this->isStatusChange = $data["is_status_change"];

        // Remove unnecessary data from the original array
        unset($data["job_id"]);
        unset($data["is_status_change"]);

        $this->data = $data;
    }

    public function handle(Request $request) : void
    {
        try {
            // Merging the data into the request
            $request->merge($this->data);

            // Define the base directory path for exports
            $filePath = "public/exports";
            $directoryPath = Storage::path($filePath);

            // Create directories if they do not exist
            if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 511, true);
            }

            // Add publisher-specific directory
            $filePath .= "/{$request->publisher_id}";
            $directoryPath = Storage::path($filePath);
            if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 511, true);
            }

            // Add website-specific directory
            $filePath .= "/{$request->website_id}";
            $directoryPath = Storage::path($filePath);
            if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 511, true);
            }

            // Prepare filename with current timestamp
            $date = strtotime(now()->toDateTimeString());
            $fileName = "/{$date}-transactions.csv";
            $fullPath = Storage::path("{$filePath}{$fileName}");

            // Create the CSV file and set permissions
            $fp = fopen($fullPath, "w");
            fwrite($fp, $fullPath);
            fclose($fp);
            chmod($fullPath, 511);

            // Create the CSV writer
            $chunkSize = 500;
            $csv = Writer::createFromPath($fullPath, "w+");

            // Insert CSV headers
            $csv->insertOne([
                "transaction_id", "transaction_date", "advertiser_name", "external_advertiser_id",
                "sale_amount", "commission_amount", "commission_status", "payment_status"
            ]);

            info("Starting export...");

            // Fetch user and transaction data
            $user = User::where("publisher_id", $request->publisher_id)->first();
            $service = new BaseService();

            // Chunk the transaction data
            $service->transactionQuery($request, $user)->chunk($chunkSize, function ($transactions) use ($csv) {
                $rows = $transactions->map(function ($transaction) {
                    return [
                        $transaction->transaction_id ?? "-",
                        Carbon::parse($transaction->transaction_date)->format('d M Y') ?? "-",
                        $transaction->advertiser_name ?? "-",
                        $transaction->external_advertiser_id ?? "-",
                        $transaction->sale_amount ?? "-",
                        $transaction->commission_amount ?? "-",
                        $transaction->commission_status ?? "-",
                        $transaction->payment_status ?? "-"
                    ];
                })->toArray();

                // Insert rows into the CSV
                $csv->insertAll($rows);
                info("Processed " . count($rows) . " records...");
            });

            // Update or create the ExportFiles record
            ExportFiles::updateOrCreate(
                [
                    "publisher_id" => $request->publisher_id,
                    "website_id" => $request->website_id,
                    "path" => str_replace("public/", '', "{$filePath}/{$fileName}"),
                    "expired_at" => now()->addMonth()->toDateTimeString()
                ],
                [
                    "name" => "Transaction Export File"
                ]
            );

            info("Export completed. File saved at: " . $fullPath);

            // Attempt to fetch daily task data
            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange, false, true);
        } catch (\Throwable $exception) {
            // Log any exceptions encountered during the process
            Methods::catchBodyFetchDaily("EXPORT TRANSACTION FILE", $exception, $this->jobID, false, true);
        }
    }
}
