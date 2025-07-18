<?php

namespace App\Traits;

use App\Classes\RandomStringGenerator;
use App\Models\DeeplinkTrackingCode;

trait DeepLink
{
    public function generateShortLink()
    {
        $generator = new RandomStringGenerator();
        $tokenLength = rand(6, 12);
        $code = $generator->generate($tokenLength);

        $trackCode = DeeplinkTrackingCode::where('code', $code)->exists();
        if($trackCode)
        {
            return $this->generateShortLink();
        }
        DeeplinkTrackingCode::updateOrCreate(
            ['code' => $code],
            ['digit' => strlen($code)]
        );
        return route("track.deeplink", ['code' => $code]);
    }
    public function generateLongLink($linkmid, $linkaffid, $subID, $ued)
    {
        return route("track.deeplink.long", ["linkmid" => $linkmid, "linkaffid" => $linkaffid, "subid" => $subID, "ued" => $ued]);
    }
}
