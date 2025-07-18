<?php

namespace App\Plugins\Traderdoubler;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\AdvertiserConfig;

trait RequestTrait
{
    /**
     * @return array
     */
    public function getTradedoublerConfigData(): array
    {
        $configs = AdvertiserConfig::select(["key", "value"])->where("name", Vars::TRADEDOUBLER)->get()->pluck("value", "key")->toArray();

        $grant_type = $configs["grant_type"] ?? null;
        $token = $configs["token"] ?? null;
        $username = $configs["username"] ?? null;
        $password = $configs["password"] ?? null;

        return [
            'type' => $grant_type,
            'token' => $token,
            'username' => $username,
            'password' => $password
        ];
    }

    public function getTradedoublerGenerateToken()
    {
        $url = "{$this->getTradedoublerDomain()}/uaa/oauth/token";
        $config = $this->getTradedoublerConfigData();
        $token = $config['token'];
        $username = urlencode($config['username']);
        $password = urlencode($config['password']);
        $type = $config['type'];

        $field = "grant_type={$type}&username={$username}&password={$password}";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $field,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic {$token}",
                "Content-Type: application/x-www-form-urlencoded",
                "Connection: close"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
//            Methods::customTradedoubler("TRADEDOUBLER GENERATE TOKEN CURL ERROR", $error_msg);
        }

        return json_decode($response, true);
    }

    public function getTradedoublerRequest($token, $url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer {$token}"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;

    }

    /**
     * @return string
     */
    public function getTradedoublerDomain($key = 1): string
    {
        if($key == 1)
            return "https://connect.tradedoubler.com";
        else
            return "https://api.tradedoubler.com/1.0";
    }
}
