<?php

namespace App\Http\Controllers\Doc\Transaction;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{

    protected function transactionFields()
    {
        return [
            "transaction_id",
            "advertiser_id",
            "internal_advertiser_id",
            "website_id",
            "publisher_id",
            "internal_payment_id",
            "commission_status",
            "payment_status",
            "commission_amount",
            "commission_amount_currency",
            "sale_amount",
            "sale_amount_currency",
            "advertiser_name",
            "transaction_date",
            "commission_type",
            "url",
            "sub_id"
        ];
    }

}
