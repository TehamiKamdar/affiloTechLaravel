<?php
namespace App\Services\Publisher\Reporting\Transaction;

use App\Helper\Vars;
use App\Models\GenerateExportRequest;
use Illuminate\Http\Request;

class ExportService extends BaseService
{
    public function init(Request $request)
    {
        $user = $request->user();

        GenerateExportRequest::updateOrCreate([
            "path" => "GenerateTransactionExportRequestJob",
            "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
            "queue" => Vars::ADMIN_WORK,
            "source" => Vars::GLOBAL,
            "format" => $request->export_format,
            "publisher_id" => $user->publisher_id,
            "website_id" => $user->active_website_id
        ], [
            "name" => "Generate Export Request Job",
            "payload" => json_encode([
                "format" => $request->export_format,
                "publisher_id" => $user->publisher_id,
                "website_id" => $user->active_website_id,
                "search" => $request->search,
                "status" => $request->status,
                "region" => $request->region,
                "advertiser" => $request->advertiser,
                "date" => $request->date
            ]),
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
        ]);

        return true;
    }
}
