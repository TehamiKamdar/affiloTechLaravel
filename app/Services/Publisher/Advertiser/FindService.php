<?php
namespace App\Services\Publisher\Advertiser;

use Illuminate\Http\Request;

class FindService extends BaseService
{
    public function init(Request $request)
    {
        $data = $this->advertiserLogic($request);

        if ($request->ajax()) {
            return $data;
        }

        $advertisers = $data['advertisers'];
        $advertisersCheckboxValues = $data['advertisersCheckboxValues'];
        $to = $data['to'];
        $from = $data['from'];
        $total = $data['total'];
        $categories = $data['categories'];
        $methods = $data['methods'];
        $countries = $data['countries'];
        $defaultStatus = $data['defaultStatus'];

        $title = "Find Advertisers";

        seo()
            ->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Advertisers',
            $title
        ];

        return view("publisher.advertisers.find", compact(
            'title', 'headings', 'advertisers', 'to', 'from', 'categories', 'methods', 'countries', 'defaultStatus', 'advertisersCheckboxValues'
        ));
    }
}
