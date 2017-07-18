<?php

namespace App\Services\UserProvider\Providers;

class Google extends UserProviderAbstract
{
    private static $endpoint = 'https://www.googleapis.com/oauth2/v3/tokeninfo?access_token={access_token}';

    public function __construct(array $config = null)
    {
    }

    public static function validate($accessToken)
    {
        $endpoint = str_replace('{access_token}', $accessToken, self::$endpoint);

        try {
            file_get_contents($endpoint);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
