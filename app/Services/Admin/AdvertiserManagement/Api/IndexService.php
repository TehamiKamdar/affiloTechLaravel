<?php

namespace App\Service\Admin\AdvertiserManagement\Api;

use App\Models\Advertiser;
use App\Models\Country;
use App\Traits\Action;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class IndexService
{
    use Action;

    public function init(Request $request)
    {
        if ($request->ajax()) {
            $editGate = "crm_api_advertiser_edit";
            $viewGate = "crm_api_advertiser_show";
            $deleteGate = "crm_api_advertiser_delete";
            $crudRoutePart = "admin.advertiser-management.api-advertisers";

            $actionData = [
                "crud_part" => $crudRoutePart,
                "view" => $viewGate,
                "edit" => $editGate,
                "delete" => $deleteGate
            ];

            return $this->prepareListing($request, $actionData);
        }

        SEOMeta::setTitle(trans("advertiser.api-advertiser.title") . " " . trans("global.list"));

        $countries = Country::orderBy("name", "ASC")->get()->toArray();

        return view("template.admin.advertisers.api.index", compact("countries"));
    }

    private function prepareListing($request, $actionData)
    {
        $queryData = $this->query($request);
        $data = [];

        foreach ($queryData["query"] as $row) {
            $actionData["id"] = $row->id;
            $data[] = $this->prepareData($actionData, $row);
        }

        $json_data = [
            "draw" => intval($request->input("draw")),
            "recordsTotal" => intval($queryData["total_data"]),
            "recordsFiltered" => intval($queryData["total_filtered"]),
            "data" => $data,
        ];

        return response()->json($json_data);
    }

    private function query(Request $request): Collection
    {
        try {
            $columns = [
                "advertiser_id",
                "name",
                "is_active",
                "url",
                "source",
                "click_through_url"
            ];

            $limit = $request->input("length");
            $start = $request->input("start");
            $order = $columns[$request->input("order.0.column")];
            $dir = $request->input("order.0.dir");
            $search = $request->input("search.value");

            $customQuery = Advertiser::select("*")->where("type", Advertiser::API);

            $manualUpdate = $request->input("manual_update");
            if (!empty($manualUpdate)) {
                if ($manualUpdate === "Yes") {
                    $customQuery->whereNotNull("description");
                } elseif ($manualUpdate === "No") {
                    $customQuery->whereNull("description");
                }
            }

            if ($source = $request->input("source")) {
                $customQuery->where("source", $source);
            }

            if ($country = $request->input("country")) {
                $customQuery->where("primary_regions", "LIKE", "%{$country}%");
            }

            $totalData = $customQuery->count();

            if (!empty($search)) {
                $customQuery->where("name", "LIKE", "%{$search}%");
            }

            $totalFiltered = $customQuery->count();

            $query = $customQuery
                ->offset(intval($start))
                ->limit(intval($limit))
                ->orderBy($order, $dir)
                ->groupBy("name")
                ->get();

            return collect([
                "total_filtered" => $totalFiltered,
                "total_data" => $totalData,
                "query" => $query
            ]);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    private function prepareData($actionData, $row): array
    {
        $action = $this->prepareAction($actionData);

        try {
            $aid = new HtmlString("<div class='text-center'>{$row->advertiser_id}</div>");
            $nestedData["advertiser_id"] = $aid->toHtml();

            $nestedData["name"] = $row->name ? Str::limit($row->name, 60, '...') : null;

            $url = $row->url ?? "-";
            $href = $row->url ? route("redirect.url") . "?url=" . urlencode($row->url) : "-";
            $htmlURL = new HtmlString("<a href='{$href}' target='_blank'>" . Str::limit($url, 60, '...') . "</a>");
            $nestedData["url"] = $htmlURL->toHtml();

            $source = strtoupper($row->source);
            $nestedData["source"] = new HtmlString("<div class='text-center'><span class='badge badge-danger'>{$source}</span></div>")->toHtml();

            $update = ($row->description && count($row->categories ?? [])) ? "Yes" : "No";
            $class = $update === "Yes" ? "info" : "warning";
            $nestedData["manual_update"] = new HtmlString("<div class='text-center'><span class='badge badge-{$class}'>{$update}</span></div>")->toHtml();

            $click = $row->click_through_url ? "Yes" : "No";
            $class = $click === "Yes" ? "success" : "danger";
            $nestedData["click_through_url"] = new HtmlString("<div class='text-center'><span class='badge badge-{$class}'>{$click}</span></div>")->toHtml();
            
            
             $is_active = $row->is_active ? "1" : "0";
            $class = $click === "1" ? "success" : "danger";
            $nestedData["is_active"] = new HtmlString("<div class='text-center'><span class='badge badge-{$class}'>{$click}</span></div>")->toHtml();

            $nestedData["action"] = $action;
        } catch (\Exception $exception) {
            dd($exception);
        }

        return $nestedData;
    }
}
