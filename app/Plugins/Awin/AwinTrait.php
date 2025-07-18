<?php

namespace App\Plugins\Awin;

use App\Helper\Static\Vars;
use App\Models\AdvertiserConfig;

trait AwinTrait
{
    /**
     * @return array
     */
    public function getAwinConfigData(): array
    {
        $configs = AdvertiserConfig::select(["key", "value"])->where("name", Vars::AWIN)->get()->pluck("value", "key")->toArray();
        $token = $configs["token"] ?? null;
        $id = $configs["publisher_id"] ?? null;
        return [
            'id' => $id,
            'token' => $token,
        ];
    }

    /**
     * @return mixed|null
     */
    public function sendAwinAdvertiserRequest(): mixed
    {
        $configs = $this->getAwinConfigData();
        $token = $configs["token"];
        $id = $configs["id"];
        $url = "{$this->getAwinDomain()}publishers/{$id}/programmes?relationship=joined";
        return $this->getWithToken($url, $token);
    }

    /**
     * @param array $data
     * @return mixed|null
     */
    public function sendAwinAdvertiserDetailRequest(array $data): mixed
    {
        $access = $this->getAwinConfigData();
        $url = "{$this->getAwinDomain()}publishers/{$access['id']}/programmedetails?advertiserId={$data['advertiser_id']}";
        return $this->getWithToken($url, $access['token']);
    }

    /**
     * @return mixed
     */
    public function sendAwinCouponRequest($page = 1): mixed//$idz, $page = 1): mixed
    {
        $configs = $this->getAwinConfigData();
        $token = $configs["token"];
        $id = $configs["id"];

        $url = "{$this->getAwinDomain()}publisher/{$id}/promotions";

        return $this->postWithToken($url, $token, [
            "filters" => [
//                "advertiserIds" => $idz,
                "membership" => "joined"
            ],
            "pagination" => [
                "page" => $page
            ]
        ]);
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function sendAwinTransactionRequest($startDate, $endDate): mixed
    {
        $configs = $this->getAwinConfigData();
        $token = $configs["token"];
        $id = $configs["id"];

        echo $startDate . "\n";
        echo $endDate . "\n";

        $url = "{$this->getAwinDomain()}publishers/{$id}/transactions/?startDate={$startDate}T00%3A00%3A00&endDate={$endDate}T01%3A59%3A59&timezone=UTC";

        return $this->getWithToken($url, $token);
    }

    /**
     * @param $advertiserID
     * @param $destinationURL
     * @param $campaign
     * @param $clickRef
     * @return mixed
     */
    public function sendAwinLinkRequest($advertiserID, $destinationURL, $campaign, $clickRef, $clickRef2, $clickRef3 = null): mixed
    {
        $configs = $this->getAwinConfigData();
        $token = $configs["token"];
        $id = $configs["id"];

        $url = "{$this->getAwinDomain()}publishers/{$id}/linkbuilder/generate";

        $params = [
            "campaign" => $campaign,
            "clickref" => $clickRef,
            "clickref2" => $clickRef2
        ];

        if($clickRef3)
            $params['clickref3'] = $clickRef3;

        return $this->postWithToken($url, $token, [
            "advertiserId" => $advertiserID,
            "destinationUrl" => $destinationURL,
            "parameters" => $params,
            "shorten" => false
        ]);
    }

    /**
     * @return string
     */
    private function getAwinDomain(): string
    {
        return "https://api.awin.com/";
    }
}
