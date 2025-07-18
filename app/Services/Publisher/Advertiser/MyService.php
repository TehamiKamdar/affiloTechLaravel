<?php

namespace App\Services\Publisher\Advertiser;

use Illuminate\Http\Request;

class MyService extends BaseService
{
    public function init(Request $request)
    {
        $data = $this->advertiserLogic($request);

        if ($request->ajax()) {
            return $data;
        }

        $advertisers = $data['advertisers'];
        $to = $data['to'];
        $from = $data['from'];
        $categories = $data['categories'];
        $methods = $data['methods'];
        $countries = $data['countries'];
        $defaultStatus = $data['defaultStatus'];
        $advertisersCheckboxValues = $data['advertisersCheckboxValues'];

        $title = "My Advertisers";

        seo()->title("{$title} — " . env("APP_NAME"));

        $headings = [
            'Advertisers',
            $title
        ];
        // return $advertisers;
        return view("publisher.advertisers.find", compact('title', 'headings', 'advertisers', 'to', 'from', 'categories', 'methods', 'countries', 'defaultStatus', 'advertisersCheckboxValues'));
    }
}
