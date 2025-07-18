<?php
namespace App\Jobs\Export;

use App\Helper\Methods;
use App\Models\ExportFiles;
use App\Models\User;
use App\Services\Publisher\Advertiser\BaseService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Request;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;

class AdvertiserExport implements ShouldQueue
{
    use Queueable;

    protected $data, $jobID, $isStatusChange;

    public function __construct($data)
    {
        $this->jobID = $data["job_id"];
        $this->isStatusChange = $data["is_status_change"];
        
        // Removing unnecessary data from the original array
        unset($data["job_id"]);
        unset($data["is_status_change"]);
        
        $this->data = $data;
    }

    public function handle(Request $request) : void
    {
        try {
            // Merging the data into the request
            $request->merge($this->data);
            info(json_encode($request->all()));

            // Define the file path where the export will be stored
            $filePath = "public/exports";
            $directoryPath = Storage::path($filePath);

            // Create directories if they do not exist
            if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 511, true);
            }

            // Adding publisher-specific and website-specific directories
            $filePath .= "/{$request->publisher_id}";
            $directoryPath = Storage::path($filePath);
            if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 511, true);
            }

            $filePath .= "/{$request->website_id}";
            $directoryPath = Storage::path($filePath);
            if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 511, true);
            }

            // Prepare the filename with the current date
            $date = strtotime(now()->toDateTimeString());
            $fileName = "/{$date}-advertisers.csv";
            $fullPath = Storage::path("{$filePath}{$fileName}");

            // Create the file and set appropriate permissions
            $fp = fopen($fullPath, "w");
            fwrite($fp, $fullPath);
            fclose($fp);
            chmod($fullPath, 511);

            // Create the CSV writer and insert headers
            $chunkSize = 500;
            $csv = Writer::createFromPath($fullPath, "w+");
            $csv->insertOne([
                "ID", "SID", "Name", "Primary Regions", "Logo", "Commission", "Status", "Locked Status"
            ]);

            info("Starting export...");

            // Fetch user and advertiser data
            $user = User::where("publisher_id", $request->publisher_id)->first();
            $service = new BaseService();

            // If no status is provided, set the default statuses
            if (empty($request->status)) {
                $request->merge([
                    "status" => implode(",", array_keys($service->defaultStatuses($request)))
                ]);
            }

            // Fetch and chunk advertiser data
            $service->findAdvertiserQuery($request, $user)->chunk($chunkSize, function ($advertisers) use ($csv) {
                $rows = $advertisers->map(function ($advertiser) {
                    return [
                        $advertiser->id,
                        $advertiser->sid,
                        $advertiser->name,
                         implode(", ", is_array($advertiser->primary_regions) ? $advertiser->primary_regions : []),
                        $advertiser->logo
                            ? Methods::staticAsset("storage/{$advertiser->logo}")
                            : Methods::staticAsset("assets/media/logos/placeholder.jpg"),
                        $advertiser->commission . " " . $advertiser->commission_type,
                        $advertiser->status,
                        $advertiser->locked_status
                    ];
                })->toArray();

                // Insert rows into the CSV
                $csv->insertAll($rows);
                info("Processed " . count($rows) . " records.");
            });

            // Update or create an entry in the ExportFiles model
            ExportFiles::updateOrCreate(
                [
                    "publisher_id" => $request->publisher_id,
                    "website_id" => $request->website_id,
                    "path" => str_replace("public/", '', "{$filePath}/{$fileName}"),
                    "expired_at" => now()->addMonth()->toDateTimeString()
                ],
                [
                    "name" => "Advertiser Export File"
                ]
            );

            info("Export completed. File saved at: " . $fullPath);

            // Attempt to fetch daily task data
            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange, false, true);
        } catch (\Throwable $exception) {
            // Log any exceptions encountered during the process
            Methods::catchBodyFetchDaily("EXPORT ADVERTISER FILE", $exception, $this->jobID, false, true);
        }
    }
}
