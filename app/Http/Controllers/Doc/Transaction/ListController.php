<?php

namespace App\Http\Controllers\Doc\Transaction;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Http\Requests\Doc\TransactionRequest;
use App\Http\Resources\Doc\Transactions\TransactionCollection;
use App\Models\ApiHistory;
use App\Models\PaymentHistory;
use App\Models\Transaction;
use App\Models\Website;

/**
 * @group Transactions
 *
 * APIs for managing Transactions
 *
 * @authenticated
 */
class ListController extends BaseController
{
    /**
     * Get All Transactions
     *
     * This endpoint is used to fetch all available transactions from the database through authentication.
     *
     * @response scenario="Get All Transactions"
     * {
     * "data": [
     * {
     * "transaction_id": "10789.5686.1207059",
     * "advertiser_id": 94731544,
     * "advertiser_name": "KitchenAid Australia",
     * "publisher_id": 14447196,
     * "publisher_name": "Zain ul Abedin",
     * "website_id": 90644632,
     * "website_name": "*******.com.au/",
     * "payment_id": null,
     * "commission_status": "Pending",
     * "commission_amount": "5.03",
     * "commission_amount_currency": "USD",
     * "sale_amount": "167.77",
     * "sale_amount_currency": "USD",
     * "transaction_date": "2023-07-27 23:41:30",
     * "commission_type": "CLICK_COOKIE",
     * "url": null,
     * "sub_id": "aaf265c2-7385-4a3a-9d75-991f3e284064",
     * },
     * {...............},
     * ]
     *  "pagination": {
     *  "total": 832,
     *  "count": 50,
     *  "per_page": 50,
     *  "current_page": 1,
     *  "total_pages": 17
     *  }
     * }
     */
    public function __invoke(TransactionRequest $request)
    {
        $limit = $request->limit ?? Vars::LIMIT_20;
        $user = Methods::getDocUser($request);
        $website = Website::where('wid', $request->wid)->first();
        $transactions = Transaction::with([
            'advertiser:id,sid', 'publisher:id,sid,first_name,last_name', 'website:id,wid,name'
        ])->select($this->transactionFields())->where("publisher_id", $user->publisher_id)
            ->where("website_id", $website->id);

        if($request->start_date)
            $transactions = $transactions->whereDate('transaction_date', '>=', $request->start_date);

        if($request->end_date)
            $transactions =  $transactions->whereDate('transaction_date', '<=', $request->end_date);

        if ($request->transaction_id)
            $transactions = $transactions->where('transaction_id', $request->transaction_id);

        if($request->payment_id)
        {
            $payment = PaymentHistory::select('transaction_idz')->where('payment_id', $request->payment_id)->first();
            $transactions = $transactions->whereIn("transaction_id", $payment->transaction_idz);
        }

        $transactions = $transactions->orderBy("transaction_date","DESC")->paginate($limit);

        ApiHistory::create([
            "publisher_id" => $user->publisher_id,
            "website_id" => $website->id,
            "wid" => $request->wid,
            "name" => "List Transactions",
            "token" => $request->header('token'),
            "page" => $request->page,
            "limit" => $limit,
            "ip" => request()->ip(),
        ]);

        return new TransactionCollection($transactions);
    }
}
