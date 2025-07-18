<?php
namespace App\Services\Admin;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TransactionService
{
    public function ajax(Request $request)
    {
        $transactions = Transaction::select([
            'id', 'transaction_id', 'advertiser_name', 'transaction_date', 'customer_country',
            'advertiser_country', 'commission_status', 'payment_status', 'commission_amount',
            'commission_amount_currency', 'sale_amount', 'received_commission_amount', 'received_sale_amount',
            'sale_amount_currency', 'received_commission_amount_currency', 'source'
        ])->orderBy('transaction_date', 'desc');

        return DataTables::of($transactions)
            ->addColumn('action', fn($transaction) => $this->getActionButtons($transaction->id))
            ->editColumn('commission_status', fn($transaction) => ucwords(strtolower($transaction->commission_status)))
              ->editColumn('payment_status', fn($transaction) => $transaction->payment_status == 1 ? 'Approved' : 'Pending')
              ->editColumn('advertiser_country', fn($transaction) => $transaction->advertiser_country == null ? '-' : $transaction->advertiser_country)
              ->editColumn('customer_country', fn($transaction) => $transaction->customer_country == null ? '-' : $transaction->customer_country)
            ->editColumn('source', fn($transaction) => ucwords(strtolower($transaction->source)))
            ->editColumn('created_at', fn($transaction) => Carbon::parse($transaction->transaction_date)->format('Y-m-d'))
            ->rawColumns(['action'])
            ->make(true);
    }
    
    
    public function missingajax(Request $request)
    {
         $transactions = Transaction::select([
            'id', 'transaction_id', 'advertiser_name', 'transaction_date', 'customer_country',
            'advertiser_country', 'commission_status', 'payment_status', 'commission_amount',
            'commission_amount_currency', 'sale_amount', 'received_commission_amount', 'received_sale_amount',
            'sale_amount_currency', 'received_commission_amount_currency', 'source'
        ])->where('website_id',null)->where('publisher_id',null)->orderBy('transaction_date', 'desc');

        return DataTables::of($transactions)
            ->addColumn('action', fn($transaction) => $this->getMissingActionButtons($transaction->id))
            ->editColumn('commission_status', fn($transaction) => ucwords(strtolower($transaction->commission_status)))
              ->editColumn('payment_status', fn($transaction) => $transaction->payment_status == 1 ? 'Approved' : 'Pending')
              ->editColumn('advertiser_country', fn($transaction) => $transaction->advertiser_country == null ? '-' : $transaction->advertiser_country)
              ->editColumn('customer_country', fn($transaction) => $transaction->customer_country == null ? '-' : $transaction->customer_country)
            ->editColumn('source', fn($transaction) => ucwords(strtolower($transaction->source)))
            ->editColumn('created_at', fn($transaction) => Carbon::parse($transaction->transaction_date)->format('Y-m-d'))
            ->rawColumns(['action'])
            ->make(true);
    }

    private function getActionButtons($transactionId)
    {
        // $editUrl = route("admin.transactions.edit", ['transaction' => $transactionId]);
        $viewUrl = route("admin.transactions.view", ['transaction' => $transactionId]);
        // $deleteUrl = route("admin.transactions.delete", ['transaction' => $transactionId]);

        // return '
        //     <a href="' . $editUrl . '" class="btn btn-sm btn-glow-primary btn-primary">Edit</a>
        //     <a href="' . $viewUrl . '" class="btn btn-sm btn-glow-secondary btn-secondary">View</a>
        //     <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
        //         ' . csrf_field() . '
        //         ' . method_field('DELETE') . '
        //         <button type="submit" class="btn btn-sm btn-glow-danger btn-danger">Delete</button>
        //     </form>';

        return '<a href="' . $viewUrl . '" class="btn btn-sm btn-glow-secondary btn-secondary">View</a>';
    }
    
    private function getMissingActionButtons($transactionId)
    {
        // $editUrl = route("admin.transactions.edit", ['transaction' => $transactionId]);
        $viewUrl = route("admin.transactions.view", ['transaction' => $transactionId]);
        // $deleteUrl = route("admin.transactions.delete", ['transaction' => $transactionId]);

        return '
            <a class="btn btn-primary btn-xs" href="javascript:void(0)" data-toggle="modal" data-target="#missing-modal" onclick="openModal(`'.$transactionId.'`)">
                    Assign
                  </a>
            <a href="' . $viewUrl . '" class="btn btn-sm btn-glow-secondary btn-secondary">View</a>';
    }
}
