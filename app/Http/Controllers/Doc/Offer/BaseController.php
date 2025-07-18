<?php

namespace App\Http\Controllers\Doc\Offer;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{

    protected function couponFields()
    {
        return [
            "id",
            "advertiser_id",
            "sid",
            "advertiser_name",
            "advertiser_status",
            "promotion_id",
            "type",
            "title",
            "description",
            "terms",
            "start_date",
            "end_date",
            "source",
            "url",
            "url_tracking",
            "date_added",
            "campaign",
            "code",
            "exclusive",
            "regions",
            "categories"
        ];
    }

    protected function activeAdvertiserFields()
    {
        return [
            'website_id',
        ];
    }

}
