<?php
namespace App\Http\Controllers\Publisher;
use App\Services\Publisher\Promote\CouponService;
use Illuminate\Http\Request;

class PromoteController extends BaseController
{
    public function getCoupons(Request $request, CouponService $service)
    {
        $title = "Coupons";

        seo()
            ->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Promote',
            $title
        ];

       return $service->init($request);
    }

    public function getTextLinks()
    {
        $title = "Text Links";

        seo()
            ->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Promote',
            $title
        ];

        return $this->returnComingSoonView($title, $headings);
    }

    public function getDeepLinks()
    {
        $title = "Deep Links";

        seo()
            ->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Promote',
            $title
        ];

        return $this->returnComingSoonView($title, $headings);
    }

    public function getEmbedLinks()
    {
        $title = "Embed Links";

        seo()
            ->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Promote',
            $title
        ];

        return $this->returnComingSoonView($title, $headings);
    }
}
