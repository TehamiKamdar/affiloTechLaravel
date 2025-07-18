<?php
namespace App\Services\Publisher\Advertiser;

use App\Helper\Vars;
use App\Models\GenerateExportRequest;
use Illuminate\Http\Request;

class ExportService extends BaseService
{
    public function init(Request $request)
    {
        $user = $request->user();

        GenerateExportRequest::updateOrCreate(
            [
                "path" => "GenerateAdvertiserExportRequestJob",
                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                "queue" => Vars::ADMIN_WORK,
                "source" => Vars::GLOBAL,
                "format" => $request->export_format,
                "publisher_id" => $user->publisher_id,
                "website_id" => $user->active_website_id
            ],
            [
                "name" => "Generate Export Request Job",
                "payload" => json_encode([
                    "format" => $request->export_format,
                    "route_name" => $request->route_name,
                    "publisher_id" => $user->publisher_id,
                    "website_id" => $user->active_website_id,
                    "search" => $request->search,
                    "status" => $request->status,
                    "country" => $request->country,
                    "advertiser_type" => $request->advertiser_type,
                    "categories" => $request->categories,
                    "methods" => $request->methods,
                ]),
                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
            ]
        );

        return true;
    }
}
