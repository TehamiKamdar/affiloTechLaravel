<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function default_commission(){
        $setting=Setting::where('key','default_commission')->first();
        return view('admin.setting.default_commission',compact('setting'));
    }

    public function notification(){
        $setting=Setting::where('key','notification')->first();
        return view('admin.setting.notification',compact('setting'));
    }

    public function default_commission_store(Request $request){
        $default_commission = $request->default_commission;
        Setting::create([
            'key'=>'default_commission',
            'value'=> $default_commission,
            'is_informed' => 0
        ]);
        return redirect()->route('admin.settings.default-commission');
    }

    public function notification_store(Request $request){
        $notification = $request->notification;
        Setting::create([
            'key'=>'notification',
            'value'=> $notification,
            'is_informed' => 0
        ]);
        return redirect()->route('admin.settings.notification');
    }
}
