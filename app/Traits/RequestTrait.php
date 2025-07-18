<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait RequestTrait
{
    public function get($url, $isJson = true)
    {
        $response = Http::get($url);
        return $this->makeResponse($response, $isJson);
    }
    public function post($url, $isJson = true)
    {
        $response = Http::get($url);
        return $this->makeResponse($response, $isJson);
    }

    public function getWithToken($url, $token, $isJson = true)
    {
        $response = Http::withToken($token)->get($url);
        return $this->makeResponse($response, $isJson);
    }

    public function postWithToken($url, $token, $body = [], $isJson = true)
    {
        $response = Http::withToken($token)->post($url, $body);
        return $this->makeResponse($response, $isJson);
    }

    public function getWithTokenAndHeader($url, $token, $headers = [], $isJson = true)
    {
        $response = Http::withHeaders($headers)->withToken($token)->get($url);
        return $this->makeResponse($response, $isJson);
    }

    public function postWithTokenAndHeader($url, $token, $body = [], $headers = [], $isJson = true)
    {
        $response = Http::withHeaders($headers)->withToken($token)->post($url, $body);
        return $this->makeResponse($response, $isJson);
    }

    public function postWithURLEncodeToken($url, $token, $body = [], $isJson = true)
    {
        $response = Http::withToken($token)->asForm()->post($url, $body);
        return $this->makeResponse($response, $isJson);
    }

    public function getWithBasicAuth($url, $username, $password, $headers = [], $isJson = true)
    {
        $response = Http::timeout(100000000)->withHeaders($headers)->withBasicAuth($username, $password)->get($url);
        return $this->makeResponse($response, $isJson);
    }

    public function postWithBasicAuth($url, $username, $password, $headers = [], $body = [], $isJson = true)
    {
        $response = Http::withHeaders($headers)->withBasicAuth($username, $password)->post($url, $body);
        return $this->makeResponse($response, $isJson);
    }

    private function makeResponse($response, $isJson)
    {
        if ($response->successful() && $isJson) {
            return $response->json();
        } else {
            return $response->body();
        }
    }
}
