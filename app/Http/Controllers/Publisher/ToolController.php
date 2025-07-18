<?php
namespace App\Http\Controllers\Publisher;

use App\Services\Publisher\Tools\DownloadService;
use Illuminate\Http\Request;

class ToolController extends BaseController
{
    // Link Generator
    public function getLinkGenerator()
    {
        $title = "Link Generator";

        seo()
            ->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Tools',
            $title
        ];
    
        return view('publisher.tools.deeplink_generate',compact('headings','title'));
    }

    // Feeds
    public function getFeeds()
    {
        $title = "Feeds";

        seo()
            ->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Tools',
            $title
        ];

        return $this->returnComingSoonView($title, $headings);
    }

    // API Integration
    public function getApi()
    {
        $title = "Api Integration";

        seo()
            ->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Tools',
            $title
        ];

        return $this->returnComingSoonView($title, $headings);
    }

    // Download Export Files
    public function downloadExportFiles(Request $request, DownloadService $service)
    {
        $data = $service->init($request);
        $exports = $data['exports'];
        $title = "Download Export Files";

        seo()
            ->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Tools',
            $title
        ];

        return view("publisher.tools.download", compact('exports', 'title', 'headings'));
    }
}
