<?php

namespace App\Plugins\Admitad;

use Admitad\Api\Api;
use App\Models\AdvertiserConfig;
use Carbon\Carbon;

trait AdmitadTrait
{
    protected function makeAPIInstance(): Api
    {
        $token = $this->getAdmitadAccessToken();
        return new Api($token);
    }

    /**
     * @return array
     */
    public function getAdmitadConfigData(): array
    {
        $configs = AdvertiserConfig::select(["key", "value"])->where("name", "Admitad")->get()->pluck("value", "key")->toArray();

        $clientId = $configs["client_id"] ?? null;
        $clientPassword = $configs["client_secret"] ?? null;
        $wid = $configs["ad_space_id"] ?? null;

        return [
            'client_id' => $clientId,
            'client_secret' => $clientPassword,
            'ad_space_id' => $wid
        ];
    }

    /**
     * @return string
     */
    public function getAdmitadAccessToken(): string
    {
        $config = AdvertiserConfig::where("name", "Admitad")->where("key", "token")->first();

        if(isset($findToken->updated_at) && Carbon::parse($config->updated_at)->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {

            return $findToken->value;

        } else {

            $token = $this->generateAdmitadToken();
            $this->saveAdmitadToken($token);
            return $token;

        }

    }

    /**
     * @return string
     */
    public function generateAdmitadToken(): string
    {
        $configs = $this->getAdmitadConfigData();

        $clientId = $configs["client_id"];
        $clientPassword = $configs["client_secret"];

        $scope = "advcampaigns_for_website coupons_for_website statistics public_data manage_websites deeplink_generator";

        $api = new Api();
        $response = $api->authorizeClient($clientId,$clientPassword,$scope);
        $content = $response->getContent();
        $content = json_decode($content, true);
        return $content['access_token'];
    }

    /**
     * @param string $token
     * @return void
     */
    public function saveAdmitadToken(string $token): void
    {
        AdvertiserConfig::updateOrCreate(
            [
                'name' => "Admitad",
                'key' => "token",
            ],
            [
                "name" => "Admitad",
                "value" => $token,
                "key" => "token"
            ]);
    }

    /**
     * @param string $token
     * @param int $offset
     * @return mixed
     */
    public function sendAdmitadCategoryRequest(int $offset = 0): mixed
    {
        $api = $this->makeAPIInstance();
        $data = $api->get("/categories/", array(
            'limit' =>200,
            'offset' => $offset,
        ));
        $content = $data->getContent();
        return json_decode($content, true);
    }

    /**
     * @param string $token
     * @param int $offset
     * @return mixed
     */
    public function sendAdmitadPromotionalMethodRequest(int $offset = 0): mixed
    {
        $api = $this->makeAPIInstance();
        $data = $api->get("/traffic/", array(
            'limit' => 200,
            'offset' => $offset,
        ));

        $content = $data->getContent();
        return json_decode($content, true);
    }

    /**
     * @return mixed|null
     */
    public function sendAdmitadAdvertiserRequest(string $wid, int $offset = 0): mixed
    {
        $api = $this->makeAPIInstance();
        $data = $api->get("/advcampaigns/website/{$wid}/", array(
            'limit' => 200,
            'offset' => $offset,
            'connection_status' => 'active'
        ));
        $content = $data->getContent();
        return json_decode($content, true);
    }

    /**
     * @return mixed
     */
    public function sendAdmitadCouponRequest(string $wid, int $offset = 0): mixed
    {
        $api = $this->makeAPIInstance();
        $data = $api->get("/coupons/website/{$wid}/", array(
            'limit' => 200,
            'offset' => $offset,
        ));
        $content = $data->getContent();
        return json_decode($content, true);
    }

    /**
     * @param int $offset
     * @param string $startDate
     * @return mixed
     */
    public function sendAdmitadTransactionRequest(int $offset = 0, string $startDate = '01.01.2022'): mixed
    {
        $api = $this->makeAPIInstance();
        $data = $api->get("/statistics/actions/", array(
            'limit' => 200,
            'offset' => $offset,
            'date_start' => $startDate
        ));
        $content = $data->getContent();
        return json_decode($content, true);
    }

    /**
     * @param int $advertiserID
     * @param int $wid
     * @return mixed
     */
    public function sendAdmitadLinkRequest($advertiserID, $wid): mixed
    {
        $params = [
            "websites_id" => $wid,
        ];

        $api = $this->makeAPIInstance();
        $data = $api->get("/subnetworks/v1/advcampaign/{$advertiserID}/statuses/", $params);
        $content = $data->getContent();
        return json_decode($content, true);
    }

    /**
     * @param int $advertiserID
     * @param int $wid
     * @return mixed
     */
    public function sendAdmitadCouponLinkRequest($couponID, $wid): mixed
    {
        $api = $this->makeAPIInstance();
        $data = $api->get("/coupons/{$couponID}/website/{$wid}/");
        $content = $data->getContent();
        return json_decode($content, true);
    }

    /**
     * @param $advertiserID
     * @param $destinationURL
     * @param $campaign
     * @param $clickRef
     * @param $clickRef2
     * @return mixed
     */
    public function sendAdmitadDeepLinkRequest($advertiserID, $wID, $destinationURL, $clickRef, $clickRef2, $clickRef3 = null): mixed
    {
        $api = $this->makeAPIInstance();
        $params = [
            "ulp" => $destinationURL,
            "subid" => $clickRef,
            "subid1" => $clickRef,
            "subid2" => $clickRef2,
        ];

        if($clickRef3)
            $params['subid3'] = $clickRef3;
        $data = $api->get("/deeplink/{$wID}/advcampaign/{$advertiserID}/", $params);
        $content = $data->getContent();
        return json_decode($content, true);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function sendAdmitadCreateWebsiteRequest($params): mixed
    {
        $api = $this->makeAPIInstance();
        $data = $api->post("/subnetworks/v1/websites/create/", $params);
        $content = $data->getContent();
        return json_decode($content, true);
    }

}
