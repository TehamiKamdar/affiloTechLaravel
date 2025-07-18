<?php

namespace App\Traits;

use App\Classes\RandomStringGenerator;
use App\Models\Tracking;

trait GenerateLink
{
    public function generateLink($id1, $id2)
    {
        return route("track.simple", ['advertiser' => $id1, 'website' => $id2]);
    }

    public function generateLongLink($linkmid, $linkaffid, $subID)
    {
        return route("track.simple.long", ["linkmid" => $linkmid, "linkaffid" => $linkaffid, "subid" => $subID]);
    }

    public function generateShortLink()
    {
        $generator = new RandomStringGenerator();
        $tokenLength = 8;
        $code = $generator->generate($tokenLength);
        $trackCode = Tracking::select('id')->where("tracking_url_short", route("track.short", ['code' => $code]))->count();
        if($trackCode > 0)
        {
            return $this->generateShortLink();
        }
        return route("track.short", ['code' => $code]);
    }
}
