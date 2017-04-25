<?php

namespace App\Services\UserProvider\Providers;

use Request;

class Facebook extends UserProviderAbstract
{

    private static $endpoint  =   "https://graph.facebook.com/me?access_token={access_token}";

    public function __construct(Array $config = null){}

    public static function validate($accessToken)
    {
        $endpoint = str_replace('{access_token}', $accessToken, self::$endpoint);

        try {

            file_get_contents($endpoint);
            return true;

        } catch (\Exception $e) {

            return false;
        };
    }
}