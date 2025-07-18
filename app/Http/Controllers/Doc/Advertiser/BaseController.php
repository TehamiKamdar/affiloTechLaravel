<?php

namespace App\Http\Controllers\Doc\Advertiser;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{

    protected function advertiserFields()
    {
        return "id,categories,promotional_methods,program_restrictions,sid,name,url,logo,primary_regions,supported_regions,currency_code,average_payment_time,epc,validation_days,commission,commission_type,goto_cookie_lifetime,exclusive,deeplink_enabled,description,short_description,program_policies";
    }

    protected function activeAdvertiserFields()
    {
        return [
            'advertiser_id',
            'tracking_url',
            'tracking_url_short',
            'status',
        ];
    }

}
