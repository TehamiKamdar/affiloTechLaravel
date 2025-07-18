<?php

namespace App\Plugins\Traderdoubler;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\AdvertiserConfig;

trait TradedoublerTrait
{
    use RequestTrait;

    private $tradeDoublerAccessToken = null;

    public function getTradedoublerSourceList()
    {
        $token = $this->getToken();
        $url = "{$this->getTradedoublerDomain()}";
        $url = "{$url}/publisher/sources";
        $response = $this->getTradedoublerRequest($token, $url);
        return json_decode($response, true);
    }

    public function sendTradedoublerAdvertiserRequest($sourceID, $offset)
    {
        $token = $this->getToken();
        $url = "{$this->getTradedoublerDomain()}";
        $url = "{$url}/publisher/programs?sourceId={$sourceID}&offset={$offset}&statusId=3";
        $response = $this->getTradedoublerRequest($token, $url);
        return json_decode($response, true);
    }

    public function sendTradedoublerGetAdvertiserByIDRequest($data)
    {
        $token = $this->getToken();
        $url = "{$this->getTradedoublerDomain()}";
        $url = "{$url}/publisher/programs/detail?sourceId={$data['network_source_id']}&programId={$data['advertiser_id']}";
        $response = $this->getTradedoublerRequest($token, $url);
        return json_decode($response, true);
    }

    public function sendTradedoublerGetCouponTokenRequest()
    {
        $token = $this->getToken();
        $url = "{$this->getTradedoublerDomain()}";
        $url = "{$url}/publisher/tokens";
        $response = $this->getTradedoublerRequest($token, $url);
        return json_decode($response, true);
    }

    public function sendTradedoublerGetCouponByTokenRequest($token)
    {
        $accessToken = $this->getToken();
        $url = "{$this->getTradedoublerDomain(2)}";
        $url = "{$url}/vouchers.json;dateOutputFormat=iso8601?token=$token";
        $response = $this->getTradedoublerRequest($accessToken, $url);
        return json_decode($response, true);
    }

    public function sendTradedoublerGetDeepLinkByAdvertiserIDRequest($body)
    {

    }

    public function sendTradedoublerTransactionRequest($sourceID, $fromDate, $toDate, $offset)
    {
        $token = $this->getToken();
        $url = "{$this->getTradedoublerDomain()}";
        $url = "{$url}/publisher/report/transactions?sourceId={$sourceID}&fromDate={$fromDate}&toDate={$toDate}&offset={$offset}";
        $response = $this->getTradedoublerRequest($token, $url);
        return json_decode($response, true);

    }

    public function sendTradedoublerPaymentRequest($reportID, $token, $fromDate, $toDate)
    {
        $token = $this->getToken();
        $url = "https://api.linksynergy.com/advancedreports/1.0?reportid=$reportID&token=cfee93a0224f68e6ade2d9ea6c1143e1bdf56be70e1d1244840dd34ea41f07da&bdate=20230101&edate=20231022";
        $response = $this->getTradedoublerRequest($token, $url);
        return json_decode($response, true);

    }

    public function logoURLCheck($url)
    {
        $status = false;
        if($url)
        {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_NOBODY, true);
            $result = curl_exec($curl);
            if ($result !== false)
            {
                $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                if ($statusCode != 404)
                {
                    $status = true;
                }
            }
        }
        return $status;
    }

    private function getToken()
    {
        if ($this->tradeDoublerAccessToken)
        {
            $token = $this->tradeDoublerAccessToken;
        }
        else
        {
            $token = $this->getTradedoublerGenerateToken();
            if(isset($token['access_token']))
            {
                $token = $token['access_token'];
            } else {
//                Methods::customTradedoubler("TRADEDOUBLER GET TOKEN", $token);
            }
        }

        return $token;
    }
}
