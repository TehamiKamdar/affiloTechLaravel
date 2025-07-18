<?php
namespace App\Helper;

use App\Helper\Vars;
use Illuminate\Support\Facades\Log;

class DeepLinkGenerate
{
    public function generate($advertiser, $publisherID, $websiteID, $subID, $landingURL)
    {
        $link = null;

        if (isset($advertiser->source)) {
            switch ($advertiser->source) {
                case Vars::ADMITAD:
                    $landingURL = urlencode($landingURL);
                    $url = strtok($advertiser->click_through_url, '?');
                     $link = "{$url}?ulp={$landingURL}&subid={$publisherID}&subid1={$publisherID}&subid2={$websiteID}";
                    if ($subID) {
                        $link .= "&subid3={$subID}";
                    }
 break;
                case Vars::AWIN:
                    $campaign = urlencode($advertiser->name);
                    $landingURL = urlencode($landingURL);
                    $subID = empty($subID) ? '' : $subID;
                    $link = "{$advertiser->click_through_url}&campaign={$campaign}&clickref={$publisherID}&clickref2={$websiteID}&clickref3={$subID}&clickref4=&clickref5=&clickref6=&ued={$landingURL}&platform=pl";
                    break;
                    
                 

                case Vars::IMPACT_RADIUS:
                   $landingURL = urlencode($landingURL);
                $subID = empty($subID) ? '' : $subID;
                $link = "{$advertiser->click_through_url}?DeepLink={$landingURL}&subId1={$publisherID}&subId2={$websiteID}&subId3={$subID}";
                    break;
                    
                     case Vars::DUOMAI:
                  $url = $advertiser->click_through_url;

    $parsedUrl = parse_url($url);
    $queryString = isset($parsedUrl['query']) ? $parsedUrl['query'] : '';

    // Parse query string into an array
    parse_str($queryString, $queryParams);

    // Update or add 'r' parameter
    $queryParams['euid'] = $publisherID;
    $queryParams['t'] = $landingURL;

    // Build the updated query string
    $newQueryString = http_build_query($queryParams);

    // Reconstruct the URL
    $newUrl = (isset($parsedUrl['scheme']) ? "{$parsedUrl['scheme']}://" : '') .
              (isset($parsedUrl['host']) ? "{$parsedUrl['host']}" : '') .
              (isset($parsedUrl['path']) ? "{$parsedUrl['path']}" : '') .
              '?' . $newQueryString .
              (isset($parsedUrl['fragment']) ? "#{$parsedUrl['fragment']}" : '');

    $link = $newUrl;
                    break;

                case Vars::LINKCONNECTOR:
                    $s = $subID;
                    if (empty($s)) {
                        $s = $websiteID;
                    }
                    $landingURL = urlencode($landingURL);
                    $link = "{$advertiser->click_through_url}\46\x75\x72\x6c\x75{$landingURL}\x26\x61\x74\x69\x64\x3d{$s}";
                    break;

                case Vars::PARTNERIZE:
                    $link = "{$advertiser->click_through_url}/subid1:{$publisherID}/subid2:{$websiteID}";
                    if (!empty($subID)) {
                        $link .= "/subid3:{$subID}";
                    }
                    $link .= "/destination:{$landingURL}";
                    break;
                    
                    
                     case Vars::PEPPERJAM:
          
                $sid = $subID;
                if(empty($sid))
                {
                    $sid = $websiteID;
                }
                $landingURL = urlencode($landingURL);
                $link = "{$advertiser->click_through_url}?sid={$sid}&url={$landingURL}";

       break;
                    

                case Vars::PEPPERJAM:
                    $sid = $subID;
                    if (empty($sid)) {
                        $sid = $websiteID;
                    }
                    $landingURL = urlencode($landingURL);
                    $link = "{$advertiser->click_through_url}\77\x73\x151\x144\x3d{$sid}\x26\x75\x72\x6c\x3d{$landingURL}";
                    break;
                    
                   case Vars::ECLICK:
    $sid = $subID;
    if (empty($sid)) {
        $sid = $websiteID;
    }
$landingURL = urlencode($landingURL);
    $link = $advertiser->click_through_url . $publisherID . '/' . $sid. '/' .$landingURL;
    break;

                case Vars::RAKUTEN:
                    $u1 = $subID;
                    if (empty($u1)) {
                        $u1 = $websiteID;
                    }
                    $url = $advertiser->click_through_url;
                    $parseURL = parse_url($url, PHP_URL_QUERY);
                    $queryParams = [];
                    parse_str($parseURL, $queryParams);

                    if (isset($queryParams["\x69\x64"])) {
                        $id = $queryParams["\151\x64"];
                        $mid = $advertiser->advertiser_id;
                        $link = "https://click.linksyn.com/deepLink?i={$id}&mid={$mid}&url={$landingURL}&u1={$u1}";
                    } else {
                        Log::error("RAKUTEN: URL ID NOT FOUND");
                        Log::error(json_encode($queryParams));
                    }
                    break;

                case Vars::TAKEADS:
                    $s = $subID;
                    if (empty($s)) {
                        $s = $websiteID;
                    }
                    $landingURL = urlencode($landingURL);
                    $link = "{$advertiser->click_through_url}\77\x75\x72\x6c\x3d{$landingURL}\46\x73\x3d{$s}";
                    break;

                case Vars::TRADEDOUBLER:
                    $epi = $subID;
                    if (empty($epi)) {
                        $epi = $publisherID;
                    }
                    $landingURL = urlencode($landingURL);
                      $link = "{$advertiser->click_through_url}&epi={$epi}&epi2={$websiteID}&url={$landingURL}";
                    break;
                    
                      case Vars::QUK:
                    $url = $advertiser->click_through_url;

    $parsedUrl = parse_url($url);
    $queryString = isset($parsedUrl['query']) ? $parsedUrl['query'] : '';

    // Parse query string into an array
    parse_str($queryString, $queryParams);

    // Update or add 'r' parameter
    $queryParams['sub_id'] = $publisherID;

$queryParams['sub_id2'] = $websiteID;
    // Update or add 'u' parameter
    $queryParams['url'] = $landingURL;

    // Build the updated query string
    $newQueryString = http_build_query($queryParams);

    // Reconstruct the URL
    $newUrl = (isset($parsedUrl['scheme']) ? "{$parsedUrl['scheme']}://" : '') .
              (isset($parsedUrl['host']) ? "{$parsedUrl['host']}" : '') .
              (isset($parsedUrl['path']) ? "{$parsedUrl['path']}" : '') .
              '?' . $newQueryString .
              (isset($parsedUrl['fragment']) ? "#{$parsedUrl['fragment']}" : '');

    $link = $newUrl;
                    break;
              
        case Vars::OPTIMISE:
            $url = $advertiser->click_through_url;
            $uid = $publisherID;
            $uid2 = $websiteID;
            $uid3 = $subID;
            $link = "{$advertiser->click_through_url}&UID={$uid}&UID2={$uid2}&UID3={$uid3}&UID4=&UID5=&r={$landingURL}";
         break;
case Vars::SALESGAIN:
         $url = $advertiser->click_through_url;

    $parsedUrl = parse_url($url);
    $queryString = isset($parsedUrl['query']) ? $parsedUrl['query'] : '';

    // Parse query string into an array
    parse_str($queryString, $queryParams);

    // Update or add 'r' parameter
    $queryParams['uid'] = $publisherID;
     unset($queryParams['url']);


    $newQueryString = http_build_query($queryParams);

    // Append 'url' manually without encoding
    $newQueryString .= '&url=' . $landingURL;

    // Reconstruct the URL
    $newUrl = (isset($parsedUrl['scheme']) ? "{$parsedUrl['scheme']}://" : '') .
              (isset($parsedUrl['host']) ? "{$parsedUrl['host']}" : '') .
              (isset($parsedUrl['path']) ? "{$parsedUrl['path']}" : '') .
              '?' . $newQueryString .
              (isset($parsedUrl['fragment']) ? "#{$parsedUrl['fragment']}" : '');

    $link = $newUrl;
    break;
                default:
                    $link = null;
                    break;
            }
        }

        return $link;
    }
}
