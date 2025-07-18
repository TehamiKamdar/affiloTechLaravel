<?php

namespace App\Http\Controllers\Publisher;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class APIInfoController extends BaseController
{
    public function actionApiInfo()
    {
        $user = auth()->user();
        SEOMeta::setTitle("API Information");
        
         $title = "Api Integration";

        seo()
            ->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Tools',
            $title
        ];


        return view("publisher.tools.api", compact('user','title','headings'));
    }

    public function actionApiTokenRegenerate(Request $request)
    {
        $token = $this->generateToken();
        User::where('id', auth()->user()->id)->update([
            'api_token' => $token
        ]);
        return response()->json(['token' => $token]);
    }

    private function generateToken()
    {
        $token = null;
        $loop = true;
        while($loop)
        {
            $token = Str::random(30);
            $checkToken = Methods::checkTokenAlreadyExist($token);
            if(!$checkToken)
                $loop = false;
        }
        return $token;
    }
}
