<?php

namespace App\Services\UserProvider;

interface UserProvider
{
    /**
     * Validate a access token
     * @param $provider
     * @param $accessToken
     * @return mixed
     */
    public static function validate($provider, $accessToken);
}