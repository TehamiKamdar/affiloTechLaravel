<?php
namespace App\Http\Controllers\Publisher;

use App\Services\Publisher\Finance\OverviewService;
use App\Services\Publisher\Finance\PaymentService;
use Illuminate\Http\Request;

class FinanceController extends BaseController
{
    public function getFinanceOverview(Request $request, OverviewService $service)
    {
        return $service->init($request);
    }

    public function getPayments(Request $request, PaymentService $service)
    {
        return $service->init($request);
    }
}
