<?php
namespace App\Services\Publisher\Advertiser;

use Illuminate\Http\Request;

class TopService extends BaseService
{
    public function init(Request $request)
    {
        $title = "Top Advertisers";

        seo()
            ->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Advertisers',
            $title
        ];

        return $this->returnComingSoonView($title, $headings);
    }
}
