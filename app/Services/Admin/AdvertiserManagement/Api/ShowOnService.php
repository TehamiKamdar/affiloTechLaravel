<?php

namespace App\Services\Admin\AdvertiserManagement\Api;

use App\Helper\Static\Vars;
use App\Models\Advertiser;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ShowOnService
{
    public function getShowOnView(Request $request)
    {

      
        $networks = Vars::LIST;

        if($request->ajax())
        {
            return $this->getAdvertisersByNetwork($request);
        }

        return view('admin.advertiser.show_on.advertiser', compact('networks', 'request'));//, 'publishers'));
    }

    public function storeShowOnData(Request $request) {

        try {

            if($request->search_by_network == Advertiser::MANUAL)
                $advertiser = Advertiser::where("type", $request->SearchByNetwork);

            else
                $advertiser = Advertiser::where("source", $request->SearchByNetwork);

            if($request->ids || $request->not_showed_ids) {

                $uncheckedIdz = array_diff($request->not_showed_ids ?? [],$request->ids ?? []);

                Advertiser::whereIn('id', $uncheckedIdz)->update([
                    "status" => 0,
                    "is_show"=>0,
                ]);

                if($request->ids)
                    $advertiser->whereIn("id", $request->ids)->whereNotNull('click_through_url')->update([
                        "status" => Vars::ADVERTISER_STATUS_ACTIVE,
                        "is_show"=>1
                    ]);

            }

            $response = [
                "type" => "success",
                "message" => "Advertiser API Data Successfully Updated."
            ];

        } catch (\Exception $exception) {

            $response = [
                "type" => "error",
                "message" => $exception->getMessage()
            ];

        }

        $params = [];

        if($request->page)
            $params['page'] = $request->page;

        if($request->SearchByNetwork)
            $params['search_by_network'] = $request->SearchByNetwork;

        return redirect()->route('admin.advertisers.api.show_on_publisher.index', $params)
            ->with($response['type'], $response['message']);
    }

    public function getCountriesByNetwork(Request $request)
    {
        return Country::select(['name', 'iso2'])->get();
    }

    public function getAdvertisersByNetwork(Request $request)
    {
        $perPage = 200;
        $page = $request->filled('page') ? (int)$request->page : 1;

        $advertisers = Advertiser::select(['id', 'name', 'status','is_show'])
            ->whereNotNull('name')
            ->whereNotNull('click_through_url')
            ->where(function ($query) use ($request) {
                $query->where('name', '!=', '')
                    ->orWhere('click_through_url', '!=', '');
            });

        if ($request->filled('search_by_network')) {
            $advertisers->where('source', $request->search_by_network);
        } else {
            $advertisers->where('type', Advertiser::MANUAL);
        }

        if ($request->filled('search_by_country')) {
            $advertisers->where('primary_regions', 'LIKE', '%' . $request->search_by_country . '%');
        }

        if ($request->filled('search_by_input')) {
            $advertisers->where('name', 'LIKE', '%' . $request->search_by_input . '%');
        }

        $advertisers = $advertisers->paginate($perPage, ['*'], 'page', $page);
        $returnView = view("admin.advertiser.show_on.ajax", compact('advertisers'))->render();

        return response()->json(['total' => $advertisers->total(), 'html' => $returnView]);

    }
}
