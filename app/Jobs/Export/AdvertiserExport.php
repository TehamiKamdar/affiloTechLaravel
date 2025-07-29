<?php

namespace App\Jobs\Export;

use App\Models\User;
use League\Csv\Writer;
use App\Helper\Methods;
use App\Models\ExportFiles;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Publisher\Advertiser\BaseService;

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

    public function handle(Request $request): void
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

            $date = strtotime(now()->toDateTimeString());
            $fileName = "/{$date}-advertisers.csv";
            $fullPath = Storage::path("{$filePath}{$fileName}");

            // Create the file and set appropriate permissions
            $fp = fopen($fullPath, "w");
            fwrite($fp, $fullPath);
            fclose($fp);
            chmod($fullPath, 511);

            $chunkSize = 500;
            $csv = Writer::createFromPath($fullPath, "w+");

            // ✅ Avoid SYLK bug by renaming "ID" to "Id"
            $csv->insertOne([
                "Id",
                "SID",
                "Name",
                "Primary Regions",
                "Logo",
                "Commission",
                "Status",
                "Locked Status"
            ]);

            info("Starting export...");

            $user = User::where("publisher_id", $request->publisher_id)->first();
            $service = new BaseService();

            if (empty($request->status)) {
                $request->merge([
                    "status" => implode(",", array_keys($service->defaultStatuses($request)))
                ]);
            }

            $service->findAdvertiserQuery($request, $user)->chunk($chunkSize, function ($advertisers) use ($csv) {
                $rows = $advertisers->map(function ($advertiser) {
                    // ✅ Commission logic
                    $commission = $advertiser->commission;

                    if (is_null($commission) || $commission === '') {
                        // Leave it blank
                        $commission = '';
                    } elseif (is_numeric($commission)) {
                        if ($commission < 100) {
                            $commission .= '%';
                        }
                        // else: 100 or more, leave as is
                    } else {
                        $commission = trim($commission);
                        if (!Str::contains($commission, ['$', 'USD', '%'])) {
                            $commission .= '%';
                        }
                    }

                    return [
                        $advertiser->id,
                        $advertiser->sid,
                        $advertiser->name,
                        implode(", ", is_array($advertiser->primary_regions) ? $advertiser->primary_regions : []),
                        $advertiser->logo
                            ? Methods::staticAsset("storage/{$advertiser->logo}")
                            : Methods::staticAsset("assets/affiloTech.png"),
                        $commission,
                        $advertiser->status,
                        $advertiser->locked_status
                    ];
                })->toArray();

                $csv->insertAll($rows);
                info("Processed " . count($rows) . " records.");
            });

            ExportFiles::updateOrCreate(
                [
                    "publisher_id" => $request->publisher_id,
                    "website_id" => $request->website_id,
                    "path" => str_replace("public/", '', "{$filePath}{$fileName}"),
                    "expired_at" => now()->addMonth()->toDateTimeString()
                ],
                [
                    "name" => "Advertiser Export File"
                ]
            );

            info("Export completed. File saved at: " . $fullPath);

            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange, false, true);
        } catch (\Throwable $exception) {
            Methods::catchBodyFetchDaily("EXPORT ADVERTISER FILE", $exception, $this->jobID, false, true);
        }
    }
}
