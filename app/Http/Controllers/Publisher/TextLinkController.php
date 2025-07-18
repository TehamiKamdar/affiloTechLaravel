<?php
namespace App\Http\Controllers\Publisher;

use App\Enums\ExportType;
use App\Exports\TextLinkExport;
use App\Helper\Static\Vars;
use App\Http\Controllers\Controller;
use App\Models\AdvertiserPublisher;
use App\Models\Tracking;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TextLinkController extends Controller
{
    public function actionTextLink(Request $request)
    {
        $limit = Vars::DEFAULT_PUBLISHER_TEXT_LINKS_PAGINATION;

        if (session()->has("publisher_text_link_limit")) {
            $limit = session()->get("publisher_text_link_limit");
        }

        $websites = Website::withAndWhereHas("users", function ($user) {
            return $user->where("id", auth()->user()->id);
        })
        ->where("status", Website::ACTIVE)
        ->count();

        $links = array();
        $total = 0;
        $from = 0;
$to = 0;
 $perPage = $request->get("per_page", 50);
        $page = $request->get("page", 1);
        if ($websites) {
            $links = Tracking::select([
                    "advertisers.name",
                    "advertisers.sid",
                    "advertisers.url",
                    "trackings.tracking_url_short",
                    "trackings.tracking_url_long",
                    "trackings.sub_id"
                ])
                ->join("advertisers", "advertisers.id", "trackings.advertiser_id")
                ->where("trackings.publisher_id", auth()->user()->publisher_id)
                ->where("trackings.website_id", auth()->user()->active_website_id)
                ->where(function ($query) use ($request) {
                    $query->orWhereNotNull("trackings.tracking_url_short");
                    $query->orWhereNotNull("trackings.tracking_url_long");
                });

            if ($request->search_by_name) {
                $links = $links->where(function ($query) use ($request) {
                    $query->orWhere("advertisers.name", "LIKE", "%{$request->search_by_name}%");
                    $query->orWhere("trackings.url_short", "LIKE", "%{$request->search_by_name}%");
                    $query->orWhere("advertisers.url", "LIKE", "%{$request->search_by_name}%");
                    $query->orWhere("advertisers.sid", "LIKE", "%{$request->search_by_name}%");
                    $query->orWhere("trackings.sub_id", "LIKE", "%{$request->search_by_name}%");
                });
            }

            $links = $links->orderBy("trackings.created_at", "DESC")
                ->paginate($perPage, ["*"], "page", $page);

                  $from = ($links->currentPage() - 1) * $links->perPage() + 1;
        $to = min($links->currentPage() * $links->perPage(), $links->total());

            $total = $links->total();
        } else {
            $url = route("publisher.profile.website");
            $message = "Please go to <a href='{$url}'>website settings</a> and verify your site configuration.";
            Session::put("error", $message);
            $links = new LengthAwarePaginator([], 0, $perPage, $page);
        }

 $title = "Text Links";

        seo()->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Promote',
            $title
        ];

        if ($request->ajax()) {
            $returnView = view("publisher.promote.text-link", compact("links", "total", "to", "from", "title", "headings"))
                ->render();
            return response()->json([
                "title" => $title,
                "headings" => $headings,
                "total" => $total,
                "html" => $returnView,
                'to' => $to,
                'from' => $from
            ]);
        }
    //    return $links;
        return view("publisher.promote.text-link", compact("links", "total", "to", "from", "title", "headings"));
    }

    public function actionExport(ExportType $type, Request $request)
    {
        try {
            if (ExportType::CSV == $type) {
                return Excel::download(new TextLinkExport($request), "text-links.csv");
            } elseif (ExportType::XLSX == $type) {
                return Excel::download(new TextLinkExport($request), "text-links.xlsx");
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with("error", $exception->getMessage());
        }
    }
}
