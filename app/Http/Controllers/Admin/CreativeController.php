<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Services\Admin\CouponService;
use Illuminate\Http\Request;

class CreativeController extends Controller
{
    protected $service;

    public function __construct(CouponService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return view('admin.creative.index');
    }

    public function ajax(Request $request)
    {
        return $this->service->ajax($request);
    }

    public function view($id){
        $user = Coupon::find($id);
        return view('admin.creative.view',compact('user'));
    }
}
