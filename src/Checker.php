<?php

namespace HowMAS\IsVietNamIP;

class Checker
{
    private static function isLocalhostIP($ip)
    {
        return $ip == '::1';
    }

    private static function getIPFromServer()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    public static function getIPFromAPI()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'ipv4.icanhazip.com',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public static function isVietNamIP()
    {
        $ip = self::getIPFromServer();
        if (self::isLocalhostIP($ip)) {
            $ip = self::getIPFromAPI();
        }

        if (empty($ip)) {
            return false;
        }

        echo $ip;

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://api.iplocation.net/?ip=' . trim($ip));
        $response = json_decode($response->getBody(), true);

        $isVietNamIP = isset($response['response_code']) && $response['response_code'] == 200
            && (isset($response['country_code2']) && $response['country_code2'] == "VN"
            || isset($response['country_name']) && $response['country_name'] == "Viet Nam");

        return $isVietNamIP;
    }
}
