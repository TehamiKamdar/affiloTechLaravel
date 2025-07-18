<?php

namespace App\Services\Admin;

use App\Models\Advertiser;
use App\Models\Mix;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class AdvertiserService
{

    public function index(Request $request)
    {
        $routeName = $request->route()->getName();
        $statusMap = array(
            "admin.advertisers" => "API Advertisers",
            "admin.advertisers" => "Advertisers"
        );


        $title = $statusMap[$routeName] ?? '';
        return collect(array("title" => $title));
    }

    public function ajax(Request $request)
    {
        $publishers = Advertiser::select(array(
            "id",
            "advertiser_id",
            "sid",
            "name",
            "url",
            "source",
            "click_through_url",
            "provider_status",
            "is_active"
        ))->where("type", Advertiser::API)->where('is_active', 1);
        
        

        return DataTables::of($publishers)
            ->addColumn("is_tracking_url", function ($row) {
                $is_tracking_url = $row["click_through_url"]
                    ? "<span class=\"pcode\">Yes</span>"
                    : "<span class=\"pcode\">No</span>";
                return "<div class='text-center'>{$is_tracking_url}</div>";
            })
            ->addColumn("action", function ($row) {
                $viewUrl = route("admin.advertisers.api.view", array("advertiser" => $row["id"]));
                $editUrl = route("admin.advertisers.api.edit", array("advertiser" => $row["id"]));
                $deleteUrl = '';
                return "<a href=\"{$viewUrl}\" class=\"btn-sm btn-primary btn\">View</a>" . "<a href=\"{$editUrl}\" class=\"btn-sm btn-success btn\">Edit</a>"
                    . "<form action=\"{$deleteUrl}\" method=\"POST\" style=\"display: inline-block;\">"
                    . csrf_field() . method_field("DELETE")
                    . "<button type=\"submit\" class=\"btn btn-sm btn-danger\">Delete</button></form>";
            })
            ->editColumn("url", function ($row) {
                $url = Str::limit($row["url"], 50, "...");
                return "<a href='{$url}' target='_blank' title='{$url}'>{$url}</a>";
            })
            ->editColumn("is_active", function ($row) {
                $status = strtolower($row["is_active"]);
                $value = $status == "1" || $status == "1" ? "Active" : ($status == "0"
                        ? "InActive" : ($status == "hold" || $status == "waiting for provider approval"
                            ? "label-primary" : "label-warning"));
                $class = $status == "1" || $status == "1" ? "label-success" : ($status == "0"
                        ? "label-danger" : ($status == "hold" || $status == "waiting for provider approval"
                            ? "label-primary" : "label-warning"));
                $status = ucwords($status);
                return "<div class='text-center {$class}'>{$value}</div>";
            })
            ->editColumn("source", function ($row) {
                $source = ucwords(strtolower($row["source"]));
                return "<div class='text-center'>{$source}</div>";
            })
            ->rawColumns(array("action", "url", "is_tracking_url", "source", "is_active"))
            ->make(true);
    }

    public function view(Request $request, Advertiser $advertiser)
    {
        $mix = new Mix();
        $methods = $mix->whereIn("id", $advertiser->promotional_methods ?? array())->get()->pluck("name")->toArray();
        $restrictions = $mix->whereIn("id", $advertiser->program_restrictions ?? array())->get()->pluck("name")->toArray();
        $categories = $mix->whereIn("id", $advertiser->categories ?? array())->get()->pluck("name")->toArray();
        if (!empty($advertiser->primary_regions) && is_array($advertiser->primary_regions)) {
            $primaryRegions = '<ol>';
            foreach ($advertiser->primary_regions as $region) {
                $primaryRegions .= '<li>' . e($region) . '</li>'; // e() escapes output to prevent XSS
            }
            $primaryRegions .= '</ol>';
        } else {
            $primaryRegions = '-';
        }

        if (!empty($advertiser->supported_regions)) {
            $supportedRegions = implode('', array_map(function ($region) {
                return "<li>{$region}</li>";
            }, $advertiser->supported_regions ?? array()));
            $supportedRegions = "<ol>{$supportedRegions}</ol>";
        } else {
            $supportedRegions = '-';
        }

        if (!empty($advertiser->country_full_name)) {
            $countryFullName = implode('', array_map(function ($name) {
                return "<li>{$name}</li>";
            }, $advertiser->country_full_name));
            $countryFullName = "<ol>{$countryFullName}</ol>";
        } else {
            $countryFullName = '-';
        }

        if (!empty($category)) {
            $categories = implode('', array_map(function ($category) {
                return "<li>{$category}</li>";
            }, $categories));
            $categories = "<ol>{$categories}</ol>";
        } else {
            $categories = '-';
        }

        if (!empty($method)) {
            $methods = implode('', array_map(function ($method) {
                return "<li>{$method}</li>";
            }, $methods));
            $methods = "<ol>{$methods}</ol>";
        } else {
            $methods = '-';
        }


        if (!empty($restriction)) {
            $restrictions = implode('', array_map(function ($restriction) {
                return "<li>{$restriction}</li>";
            }, $restrictions));
            $restrictions = "<ol>{$restrictions}</ol>";
        } else {
            $restrictions = '-';
        }
        return collect(array(
            "advertiser" => $advertiser,
            "methods" => $methods,
            "restrictions" => $restrictions,
            "categories" => $categories,
            "primary_regions" => $primaryRegions,
            "supported_regions" => $supportedRegions,
            "country_full_name" => $countryFullName
        ));
    }

    public function viewCommissionRates(Request $request, Advertiser $advertiser)
    {
        $advertiser->load("commissions");
        return collect(array(
            "advertiser" => $advertiser,
            "commissions" => $advertiser->commissions
        ));
    }

    public function viewTerms(Request $request, Advertiser $advertiser)
    {
        return collect(array("advertiser" => $advertiser));
    }
}
