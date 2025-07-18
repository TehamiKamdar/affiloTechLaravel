<?php

namespace App\Helper;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Traits\RequestTrait;

class LinkGenerate
{
    use RequestTrait;

    public function generate($advertiser, $publisherID, $websiteID, $subID = null)
    {
        $link = null;
       
        if($advertiser->source == Vars::ADMITAD)
        {
            $link = $advertiser->click_through_url;

            if(str_contains($link, "?"))
                $link = "{$link}&";
            else
                $link = "{$link}?";

            $link = "{$link}subid={$publisherID}&subid1={$publisherID}&subid2={$websiteID}";

            if($link && $subID)
            {
                $link = "{$link}&subid3={$subID}";
            }
        }
        elseif($advertiser->source == Vars::AWIN)
        {
            $campaign = urlencode($advertiser->name);
            $subID = empty($subID) ? '' : $subID;
            $link = "{$advertiser->click_through_url}&campaign={$campaign}&clickref={$publisherID}&clickref2={$websiteID}&clickref3={$subID}&clickref4=&clickref5=&clickref6=&platform=pl";
        }
        
        elseif($advertiser->source == Vars::DUOMAI)
        {
             $url = $advertiser->click_through_url;

    $parsedUrl = parse_url($url);
    $queryString = isset($parsedUrl['query']) ? $parsedUrl['query'] : '';

    // Parse query string into an array
    parse_str($queryString, $queryParams);

    // Update or add 'r' parameter
    $queryParams['euid'] = $publisherID;

    // Update or add 'u' parameter
    

    // Build the updated query string
    $newQueryString = http_build_query($queryParams);

    // Reconstruct the URL
    $newUrl = (isset($parsedUrl['scheme']) ? "{$parsedUrl['scheme']}://" : '') .
              (isset($parsedUrl['host']) ? "{$parsedUrl['host']}" : '') .
              (isset($parsedUrl['path']) ? "{$parsedUrl['path']}" : '') .
              '?' . $newQueryString .
              (isset($parsedUrl['fragment']) ? "#{$parsedUrl['fragment']}" : '');

    $link = $newUrl;
        }
      
        elseif($advertiser->source == Vars::IMPACT_RADIUS)
        {
            $subID = empty($subID) ? '' : $subID;
            $link = "{$advertiser->click_through_url}?subId1={$publisherID}&subId2={$websiteID}&subId3={$subID}";
        }
        elseif($advertiser->source == Vars::LINKCONNECTOR)
        {
            $s = $subID;
            if(empty($s))
            {
                $s = $websiteID;
            }
            $link = "{$advertiser->click_through_url}&atid={$s}";
        }
        elseif($advertiser->source == Vars::PARTNERIZE)
        {
            $link = "{$advertiser->click_through_url}/subid1:{$publisherID}/subid2:{$websiteID}";

            if(!empty($subID))
            {
                $link = "{$link}/subid3:{$subID}";
            }
        }
          elseif($advertiser->source == Vars::PEPPERJAM)
        {
            $sid = $subID;
            if(empty($sid))
            {
                $sid = $websiteID;
            }

            $link = "{$advertiser->click_through_url}?sid={$sid}";

        }
         elseif($advertiser->source == Vars::ECLICK)
        {
            $sid = $subID;
            if(empty($sid))
            {
                $sid = $websiteID;
            }

          $link = $advertiser->click_through_url;
          $link = "{$link}?sub1={$publisherID}&sub2={$sid}&sub3={$publisherID}&sub4={$sid}";


        }
        elseif($advertiser->source == Vars::RAKUTEN)
        {
            $url = $advertiser->click_through_url;
            $u1 = $subID;
            if(empty($u1))
            {
                $u1 = $websiteID;
            }
            if(str_contains($url, "subid")) {
                $link = str_replace("subid=0", "subid={$u1}&u1={$u1}", $url);
            }
            else {
                $link = "{$url}&subid={$u1}&u1={$u1}";
            }
        }
        elseif($advertiser->source == Vars::QUK){
            $url = $advertiser->click_through_url;

    $parsedUrl = parse_url($url);
    $queryString = isset($parsedUrl['query']) ? $parsedUrl['query'] : '';

    // Parse query string into an array
    parse_str($queryString, $queryParams);

    // Update or add 'r' parameter
    $queryParams['sub_id'] = $publisherID;

$queryParams['sub_id2'] = $websiteID;
    // Update or add 'u' parameter
    

    // Build the updated query string
    $newQueryString = http_build_query($queryParams);

    // Reconstruct the URL
    $newUrl = (isset($parsedUrl['scheme']) ? "{$parsedUrl['scheme']}://" : '') .
              (isset($parsedUrl['host']) ? "{$parsedUrl['host']}" : '') .
              (isset($parsedUrl['path']) ? "{$parsedUrl['path']}" : '') .
              '?' . $newQueryString .
              (isset($parsedUrl['fragment']) ? "#{$parsedUrl['fragment']}" : '');

    $link = $newUrl;
        }
        elseif($advertiser->source == Vars::TAKEADS)
        {
            $s = $subID;
            if(empty($s))
            {
                $s = $websiteID;
            }
            $link = "{$advertiser->click_through_url}?s={$s}";
        }
        elseif($advertiser->source == Vars::TRADEDOUBLER)
        {
            $epi = $subID;
            if(empty($epi))
            {
                $epi = $publisherID;
            }
            $link = "{$advertiser->click_through_url}&epi={$epi}&epi2={$websiteID}";
        }elseif($advertiser->source == Vars::OPTIMISE){
            $url = $advertiser->click_through_url;
            $uid = $publisherID;
            $uid2 = $websiteID;
            $uid3 = $subID;
            $link = "{$advertiser->click_through_url}&UID={$uid}&UID2={$uid2}&UID3={$uid3}&UID4=&UID5=";
        }elseif($advertiser->source == Vars::SALESGAIN){
             $url = $advertiser->click_through_url;
return $url;
    $parsedUrl = parse_url($url);
    $queryString = isset($parsedUrl['query']) ? $parsedUrl['query'] : '';

    // Parse query string into an array
    parse_str($queryString, $queryParams);

    // Update or add 'r' parameter
    $queryParams['uid'] = $publisherID;
    

    // Build the updated query string
    $newQueryString = http_build_query($queryParams);

    // Reconstruct the URL
    $newUrl = (isset($parsedUrl['scheme']) ? "{$parsedUrl['scheme']}://" : '') .
              (isset($parsedUrl['host']) ? "{$parsedUrl['host']}" : '') .
              (isset($parsedUrl['path']) ? "{$parsedUrl['path']}" : '') .
              '?' . $newQueryString .
              (isset($parsedUrl['fragment']) ? "#{$parsedUrl['fragment']}" : '');

    $link = $newUrl;
        }
        return $link;
    }

    public function oldGenerate($advertiser, $publisherID, $websiteID, $subID = null)
    {
        $link = null;
        if($advertiser->source == Vars::ADMITAD)
        {
            $link = $advertiser->click_through_url;

            if(str_contains($link, "?"))
                $link = "{$link}&";
            else
                $link = "{$link}?";

            $link = "{$link}subid={$publisherID}&subid1={$publisherID}&subid2={$websiteID}";

            if($link && $subID)
            {
                $link = "{$link}&subid3={$subID}";
            }
        }
        elseif($advertiser->source == Vars::AWIN)
        {
            $destinationURL = $advertiser->url;
            $data = $this->sendAwinLinkRequest($advertiser->advertiser_id, $destinationURL, $advertiser->name, $publisherID, $websiteID, $subID);
            $link = $data['url'] ?? null;

            if(empty($link))
            {
                $campaign = urlencode($advertiser->name);
                $destinationURL = urlencode($destinationURL);
                $subID = empty($subID) ? '' : $subID;
                $link = "{$advertiser->click_through_url}&campaign={$campaign}&clickref={$publisherID}&clickref2={$websiteID}&clickref3={$subID}&clickref4=&clickref5=&clickref6=&ued={$destinationURL}&platform=pl";
                Methods::customLinkGenerate("AWIN MANUAL GENERATE LINK", "LINK: {$link}");
            }
            else
            {
                Methods::customLinkGenerate("AWIN AUTOMATE GENERATE LINK", "LINK: {$link}");
            }

        }
        elseif($advertiser->source == Vars::IMPACT_RADIUS)
        {
            $data = $this->sendImpactRadiusLinkRequest($advertiser->advertiser_id, $publisherID, $websiteID, $subID);
            $link = $data['TrackingURL'] ?? null;

            if(empty($link))
            {
                $subID = empty($subID) ? '' : $subID;
                $link = "{$advertiser->click_through_url}?subId1={$publisherID}&subId2={$websiteID}&subId3={$subID}";
                Methods::customLinkGenerate("IMPACT RADIUS MANUAL GENERATE LINK", "LINK: {$link}");
            }
            else
            {
                Methods::customLinkGenerate("IMPACT RADIUS AUTOMATE GENERATE LINK", "LINK: {$link}");
            }
        }
        elseif($advertiser->source == Vars::RAKUTEN)
        {
            $url = $advertiser->click_through_url;
            $u1 = $subID;
            if(empty($u1))
            {
                $u1 = $websiteID;
            }
            if(str_contains($url, "subid")) {
                $link = str_replace("subid=0", "subid={$u1}&u1={$u1}", $url);
            }
            else {
                $link = "{$url}&subid={$u1}&u1={$u1}";
            }
            Methods::customLinkGenerate("RAKUTEN MANUAL GENERATE LINK", "LINK: {$link}");
        }
        elseif($advertiser->source == Vars::TRADEDOUBLER)
        {
            $epi = $subID;
            if(empty($epi))
            {
                $epi = $publisherID;
            }
            $link = "{$advertiser->click_through_url}&epi={$epi}&epi2={$websiteID}";
            Methods::customLinkGenerate("TRADEDOUBLER MANUAL GENERATE LINK", "LINK: {$link}");
        }

        if(empty($link)) {
            $link = $advertiser->click_through_url;
            Methods::customLinkGenerate("MANUAL GENERATE LINK", "LINK: {$link}");
        }

        return $link;
    }
}
