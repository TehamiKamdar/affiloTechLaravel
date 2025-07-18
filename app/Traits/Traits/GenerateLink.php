<?php

namespace App\Traits;

use App\Classes\RandomStringGenerator;
use App\Models\DelTracking;
use App\Models\GenerateTrackingCode;
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
        $tokenLength = rand(6, 12);
        $code = $generator->generate($tokenLength);
        $trackCode = GenerateTrackingCode::where('code', $code)->exists();
        if($trackCode)
        {
            return $this->generateShortLink();
        }
        GenerateTrackingCode::updateOrCreate(
            ['code' => $code],
            ['digit' => strlen($code)]
        );
        return route("track.short", ['code' => $code]);
    }
}
