<?php

namespace KibEv\LaravelHifiApi;

use Illuminate\Support\Facades\Cache;

class LaravelHifiApi
{
    public $username;
    public $password;
    public $ipAddress;
    public $language;

    public function __construct()
    {
        $this->username = config('laravel-hifi-api.username', '');
        $this->password = config('laravel-hifi-api.password', '');
        $this->ipAddress = config('laravel-hifi-api.ip-address', '');
        $this->language = config('laravel-hifi-api.language', 'en-GB');

        $this->updateSessionToken();
    }

    public function login()
    {
        return $this->authRequest('https://b2b.hifi-filter.com/api/authentication/login', [
            'username' => $this->username,
            'password' => $password ?? $this->password,
            'ip' => $this->ipAddress,
        ]);
    }

    public function updateSessionToken($force = false)
    {
        if($this->getSessionToken() == null || $force) {
            Cache::forget('hifi_session_token');

            Cache::rememberForever('hifi_session_token', function () {
                $data = json_decode($this->login(), true);
                return $data['sessionToken'] ?? null;
            });
        }
    }

    private function getSessionToken()
    {
        return Cache::get('hifi_session_token');
    }

    public function logout()
    {
        return $this->authRequest('https://b2b.hifi-filter.com/api/authentication/logout', [
            'username' => $this->username,
            'token' => $this->getSessionToken(),
        ]);
    }

    public function articleSearch($reference)
    {
        return $this->sendGetRequest('https://b2b.hifi-filter.com/api/article/search?reference=' . $reference);
    }

    public function articlePrice($reference, $quantity = 1)
    {
        return $this->sendPostRequest('https://b2b.hifi-filter.com/api/article/price', [
            ['reference' => $reference, 'quantity' => $quantity]
        ]);
    }

    public function articleAttributes($reference)
    {
        return $this->sendGetRequest('https://b2b.hifi-filter.com/api/article/filter/' . $reference);
    }

    public function articleAvailableQuantity($reference, int $quantity = 1)
    {
        return $this->sendPostRequest('https://b2b.hifi-filter.com/api/article/availability', [
            ['reference' => $reference, 'quantity' => $quantity]
        ]);
    }

    public function articleTechnicalSheet($reference)
    {
        return $this->sendGetRequest('https://b2b.hifi-filter.com/api/article/technicalSheet/' . $reference);
    }

    private function authRequest($url, $data = null)
    {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $headers = array(
            "Content-Type: application/x-www-form-urlencoded",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    private function sendPostRequest($url, $data = null)
    {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $headers = array(
            "Content-Type: application/json",
            "token: " . $this->getSessionToken(),
            "login: " . $this->username,
            "language: " . $this->language,
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    private function sendGetRequest($url)
    {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Content-Type: application/x-www-form-urlencoded",
            "token: " . $this->getSessionToken(),
            "login: " . $this->username,
            "language: " . $this->language,
        );

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

}
