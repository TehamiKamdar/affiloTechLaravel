<?php

namespace App\Traits;

use App\Classes\RandomStringGenerator;
use App\Models\DeeplinkTracking;
trait DeepLink
{
    public function generateShortLink()
    {
        $generator = new RandomStringGenerator();
        $tokenLength = 8;
        $code = $generator->generate($tokenLength);
        $trackCode = DeeplinkTracking::select('id')->where("tracking_url", route("track.deeplink", ['code' => $code]))->count();
        if($trackCode > 0)
        {
            return $this->generateShortLink();
        }
        return route("track.deeplink", ['code' => $code]);
    }
    public function generateLongLink($linkmid, $linkaffid, $subID, $ued)
    {
        return route("track.deeplink.long", ["linkmid" => $linkmid, "linkaffid" => $linkaffid, "subid" => $subID, "ued" => $ued]);
    }
}
