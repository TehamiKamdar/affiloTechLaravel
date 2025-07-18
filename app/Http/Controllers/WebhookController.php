<?php
namespace App\Http\Controllers;

use App\Helper\Vars;
use App\Models\FetchDailyData;
use App\Models\Setting;
use App\Models\SyncData;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function actionIncomingWebhook(Request $request)
    {
        // Update or create a SyncData record
        SyncData::updateOrCreate(
            [
                'date' => now()->format("Y-m-d H:i"),
                'source' => $request->source,
                'type' => $request->type
            ],
            [
                'name' => $request->name
            ]
        );

        // Check for a specific setting to perform further actions
        $setting = Setting::where('key', 'daily_fetch')
            ->where('value', 0)
            ->first();

        if ($setting) {
            // Update or create FetchDailyData records
            
            FetchDailyData::updateOrCreate(
                [
                    "path" => "SyncStatusChangeJob",
                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                    "key" => 1,
                    "queue" => Vars::ADMIN_WORK,
                    "source" => Vars::GLOBAL
                ],
                [
                    "name" => "Sync Status Change Job for API Server",
                    "payload" => json_encode([
                        "status" => 1,
                    ]),
                    "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                ]
            );

            // Update the 'daily_fetch' setting
            Setting::updateOrCreate(
                ['key' => 'daily_fetch'],
                ['value' => 1]
            );
        }
    }
}


