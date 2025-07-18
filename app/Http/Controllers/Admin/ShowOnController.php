<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdvertiserManagement\Api\MiscService;
use App\Services\Admin\AdvertiserManagement\Api\ShowOnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowOnController extends Controller
{
    protected object $service;
    public function __construct(ShowOnService $service)
    {
        $this->service = $service;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return View
     */
    public function index(Request $request)
    {
        return $this->service->getShowOnView($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        return $this->service->storeShowOnData($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return RedirectResponse
     */
    public function getAdvertisersByNetwork(Request $request)
    {
        return $this->service->getAdvertisersByNetwork($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return RedirectResponse
     */
    public function getCountriesByNetwork(Request $request)
    {
        return $this->service->getCountriesByNetwork($request);
    }
}
