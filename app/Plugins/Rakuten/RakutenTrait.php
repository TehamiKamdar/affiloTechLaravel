<?php

namespace App\Plugins\Rakuten;

use App\Helper\Static\Vars;
use App\Models\AdvertiserConfig;

trait RakutenTrait
{

    /**
     * @return array
     */
    public function getRakutenConfigData(): array
    {
        $configs = AdvertiserConfig::select(["key", "value"])->where("name", Vars::RAKUTEN)->get()->pluck("value", "key")->toArray();

        $scope = $configs["scope"] ?? null;
        $type = $configs["grant_type"] ?? null;
        $token = $configs["token"] ?? null;

        return [
            'scope' => $scope,
            'type' => $type,
            'token' => $token
        ];
    }

    public function getRakutenGenerateToken()
    {
        $configs = $this->getRakutenConfigData();
        $token = $configs["token"];
        $scope = $configs["scope"];
        $type = $configs["type"];
        $url = "{$this->getRakutenDomain()}/token";
        $response = $this->postWithURLEncodeToken($url, $token, [
            "scope" => $scope,
            "type" => $type
        ]);
        return $response['access_token'] ?? null;
    }

    public function sendRakutenAdvertiserRequest($page)
    {
        $token = $this->getRakutenGenerateToken();
        $url = "{$this->getRakutenDomain()}";
        $url = "{$url}/v2/advertisers?limit=200&page={$page}";
        return $this->getWithToken($url, $token);
    }

    public function sendRakutenGetAdvertiserByIDRequest($id)
    {
        $token = $this->getRakutenGenerateToken();
        $url = "{$this->getRakutenDomain()}";
        $url = "{$url}/linklocator/1.0/getMerchByID/{$id}";
        return $this->getWithToken($url, $token, false);
    }

    public function sendRakutenGetCouponByAdvertiserIDRequest($id, $pageNumber = 1)
    {
        $token = $this->getRakutenGenerateToken();
        $url = "{$this->getRakutenDomain()}";
        $url = "{$url}/coupon/1.0?mid={$id}&pagenumber={$pageNumber}";
        return $this->getWithToken($url, $token, false);
    }

    public function sendRakutenGetTextLinkByAdvertiserIDRequest($id)
    {
        $token = $this->getRakutenGenerateToken();
        $url = "{$this->getRakutenDomain()}";
        $startDate = "01012000";
        $endDate = now()->addYears(5)->format("01mY");
        $url = "{$url}/linklocator/1.0/getTextLinks/{$id}/-1/{$startDate}/{$endDate}/-1/1";
        return $this->getWithToken($url, $token, false);
    }

    public function sendRakutenGetDeepLinkByAdvertiserIDRequest($body)
    {
        $token = $this->getRakutenGenerateToken();
        $url = "{$this->getRakutenDomain()}";
        $url = "{$url}/v1/links/deep_links";
        return $this->postWithToken($url, $token, $body);
    }

    public function sendRakutenTransactionRequest($startDate, $endDate, $page = 1)
    {
        $token = $this->getRakutenGenerateToken();
        $url = "{$this->getRakutenDomain()}";
        $url = "{$url}/events/1.0/transactions?transaction_date_start={$startDate}&transaction_date_end={$endDate}&limit=100&page={$page}";
        return $this->getWithToken($url, $token);
    }

    public function sendRakutenPaymentRequest($startDate, $endDate, $securityToken, $reportID = 1, $paymentID = null, $invoiceID = null)
    {
        $token = $this->getRakutenGenerateToken();
        $url = "{$this->getRakutenDomain()}";
        $url = "{$url}/advancedreports/1.0?reportid={$reportID}&token={$securityToken}&bdate={$startDate}&edate={$endDate}";

        if($paymentID)
            $url = "{$url}&payid=$paymentID";

        if($invoiceID)
            $url = "{$url}&invoiceid=$invoiceID";

        return $this->getWithTokenAndHeader($url, $token, ['Content-Type: application/json'], false);
    }

    /**
     * @return string
     */
    public function getRakutenDomain(): string
    {
        return "https://api.linksynergy.com";
    }

    public function soapXML2JSON($xml) {
        $plainXML = $this->mungXML( trim($xml) );
        return json_decode(json_encode(SimpleXML_Load_String($plainXML, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    // FUNCTION TO MUNG THE XML SO WE DO NOT HAVE TO DEAL WITH NAMESPACE
    function mungXML($xml)
    {
        $obj = SimpleXML_Load_String($xml);
        if ($obj === FALSE) return $xml;

        // GET NAMESPACES, IF ANY
        $nss = $obj->getNamespaces(TRUE);
        if (empty($nss)) return $xml;

        // CHANGE ns: INTO ns_
        $nsm = array_keys($nss);
        foreach ($nsm as $key)
        {
            // A REGULAR EXPRESSION TO MUNG THE XML
            $rgx
                = '#'               // REGEX DELIMITER
                . '('               // GROUP PATTERN 1
                . '\<'              // LOCATE A LEFT WICKET
                . '/?'              // MAYBE FOLLOWED BY A SLASH
                . preg_quote($key)  // THE NAMESPACE
                . ')'               // END GROUP PATTERN
                . '('               // GROUP PATTERN 2
                . ':{1}'            // A COLON (EXACTLY ONE)
                . ')'               // END GROUP PATTERN
                . '#'               // REGEX DELIMITER
            ;
            // INSERT THE UNDERSCORE INTO THE TAG NAME
            $rep
                = '$1'          // BACKREFERENCE TO GROUP 1
                . '_'           // LITERAL UNDERSCORE IN PLACE OF GROUP 2
            ;
            // PERFORM THE REPLACEMENT
            $xml =  preg_replace($rgx, $rep, $xml);
        }

        return $xml;

    }
}
