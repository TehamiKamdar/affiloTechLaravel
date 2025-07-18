<?php
namespace App\Services\Publisher\Reporting\Transaction;

use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;

class IndexService extends BaseService
{
    public function init(Request $request)
    {
        $user = $request->user();

        if (auth()->user()->status != User::STATUS_ACTIVE && auth()->user()->is_completed) {
            $this->userInactiveMsg($user);
        } elseif (empty($user->active_website_id) || $user->active_website_status != Website::ACTIVE) {
            $this->websiteInactiveMsg($user);
        }

        $data = $this->transactionLogic($request);

        if ($request->ajax()) {
            return $data;
        }

        $transactions = $data->get('transactions');
        $total = $data->get('total');
        $totalTransactions = $data->get('total_transactions');
        $totalSalesAmount = $data->get('total_sales_amount');
        $totalCommissionAmount = $data->get('total_commission_amount');
        $transactionAdvertisers = $data->get('transaction_advertisers');
        $transactionCountries = $data->get('transaction_countries');
        $to = $data->get('to');
        $from = $data->get('from');

        $title = "Transactions";

        seo()->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Reporting',
            $title
        ];

        return view("publisher.reporting.transaction", compact(
            'title',
            'headings',
            'transactions',
            'total',
            'totalTransactions',
            'totalSalesAmount',
            'totalCommissionAmount',
            'transactionAdvertisers',
            'transactionCountries',
            'to',
            'from'
        ));
    }
}
