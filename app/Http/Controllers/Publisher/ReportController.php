<?php
namespace App\Http\Controllers\Publisher;

use App\Services\Publisher\Reporting\Transaction\AdvertiserPerformance;
use App\Services\Publisher\Reporting\Transaction\ClickPerformance;
use App\Services\Publisher\Reporting\Transaction\ExportService;
use App\Services\Publisher\Reporting\Transaction\IndexService;
use Illuminate\Http\Request;

class ReportController extends BaseController
{
    public function getTransactions(Request $request, IndexService $service)
    {
        return $service->init($request);
    }

    public function generateExportTransactions(Request $request, ExportService $service)
    {
        return $service->init($request);
    }

    public function getAdvertiserPerformance(Request $request, AdvertiserPerformance $service)
    {
        return $service->init($request);
    }

    public function getClickPerformance(Request $request, ClickPerformance $service)
    {
        return $service->init($request);
    }

    public function getDailyPerformance()
    {
        $title = "Daily Performance";

        seo()
            ->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Reporting',
            $title
        ];

        return $this->returnComingSoonView($title, $headings);
    }
}
