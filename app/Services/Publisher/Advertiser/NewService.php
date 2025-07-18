<?php
namespace App\Services\Publisher\Advertiser;

use Illuminate\Http\Request;

class NewService extends BaseService
{
    public function init(Request $request)
    {
        $title = "New Advertisers";

        seo()
            ->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Advertisers',
            $title
        ];

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

        // return $advertisers;
        return view("publisher.advertisers.new", compact('title', 'headings', 'advertisers', 'to', 'from', 'categories', 'methods', 'countries', 'defaultStatus', 'advertisersCheckboxValues'));
    }
}
