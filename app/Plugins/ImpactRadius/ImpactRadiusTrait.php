<?php

namespace App\Plugins\ImpactRadius;

use App\Helper\Static\Vars;
use App\Models\AdvertiserConfig;
use Illuminate\Support\Facades\Http;

trait ImpactRadiusTrait
{
    /**
     * @return array
     */
    public function getImpactRadiusConfigData(): array
    {
        $configs = AdvertiserConfig::select(["key", "value"])->where("name", Vars::IMPACT_RADIUS)->get()->pluck("value", "key")->toArray();
        $id = $configs["publisher_id"] ?? null;
        $sid = $configs["account_sid"] ?? null;
        $token = $configs["auth_token"] ?? null;
        return [
            'id' => $id,
            'sid' => $sid,
            'token' => $token
        ];
    }

    /**
     * @return mixed|null
     */
    public function sendImpactRadiusAdvertiserRequest($page = 1): mixed
    {
        $configs = $this->getImpactRadiusConfigData();
        $token = $configs["token"];
        $id = $configs["sid"];
        $url = "{$this->getImpactRadiusDomain($id)}/Campaigns?InsertionOrderStatus=Active&page={$page}";
        return $this->getWithBasicAuth($url, $id, $token, ['Accept' => 'application/json']);
    }

    /**
     * @return mixed|null
     */
    public function sendImpactRadiusAdvertiserDetailRequest($cid): mixed
    {
        $configs = $this->getImpactRadiusConfigData();
        $token = $configs["token"];
        $id = $configs["sid"];
        $url = "{$this->getImpactRadiusDomain($id)}/Campaigns/{$cid}/PublicTerms";
        return $this->getWithBasicAuth($url, $id, $token, ['Accept' => 'application/json']);
    }

    /**
     * @return mixed|null
     */
    public function sendImpactRadiusAdvertiserLogoRequest($cid): mixed
    {
        $configs = $this->getImpactRadiusConfigData();
        $token = $configs["token"];
        $id = $configs["sid"];
        $url = "{$this->getImpactRadiusDomain($id)}/Campaigns/{$cid}/Logo";
        return $this->getLogoWithBasicAuth($url, $id, $token, [], false);
    }

    /**
     * @param $advertiserID
     * @param $clickRef
     * @param $clickRef2
     * @param null $destinationURL
     * @return mixed
     */
    public function sendImpactRadiusLinkRequest($advertiserID, $clickRef, $clickRef2, $clickRef3 = null, $destinationURL = null): mixed
    {
        $configs = $this->getImpactRadiusConfigData();
        $token = $configs["token"];
        $sid = $configs["sid"];

        $url = "?subId1={$clickRef}&subId2={$clickRef2}";

        if($clickRef3)
            $url .= "&subId3=$clickRef3";

        if($destinationURL)
            $url .= "&DeepLink=$destinationURL";

        $url = "{$this->getImpactRadiusDomain($sid)}/Programs/{$advertiserID}/TrackingLinks{$url}";
        return $this->postWithBasicAuth($url, $sid, $token, ['Accept' => 'application/json']);
    }

    /**
     * @return mixed|null
     */
    public function sendImpactRadiusCouponRequest($id, $page = 1): mixed
    {
        $configs = $this->getImpactRadiusConfigData();
        $token = $configs["token"];
        $sid = $configs["sid"];
        $url = "{$this->getImpactRadiusDomain($sid)}/Campaigns/{$id}/Deals?State=ACTIVE&page={$page}";
        return $this->getWithBasicAuth($url, $sid, $token, ['Accept' => 'application/json']);
    }

    /**
     * @return mixed|null
     */
    public function sendImpactTransactionRequest($start, $end, $page): mixed
    {
        $configs = $this->getImpactRadiusConfigData();
        $token = $configs["token"];
        $id = $configs["sid"];
        $url = "{$this->getImpactRadiusDomain($id)}/Actions?StartDate={$start}&EndDate={$end}&page={$page}&PageSize=100";
        return $this->getWithBasicAuth($url, $id, $token, ['Accept' => 'application/json']);
    }

    public function getLogoWithBasicAuth($url, $username, $password, $headers = [], $isJson = true)
    {
        $response = Http::timeout(100000000)->withHeaders($headers)->withBasicAuth($username, $password)->get($url);
        return $response->getBody()->getContents();
    }

    /**
     * @return string
     */
    private function getImpactRadiusDomain($id): string
    {
        return "https://api.impact.com/Mediapartners/{$id}";
    }
}
