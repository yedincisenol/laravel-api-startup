<?php

namespace App\Services\UserProvider;

use App\Services\UserProvider\Exceptions\UserProviderNotFound;

class UserProviderClient
{
    /**
     * Validate a access token.
     *
     * @param $provider
     * @param $accessToken
     *
     * @throws UserProviderNotFound
     *
     * @return mixed
     */
    public static function validate($provider, $accessToken)
    {
        $providerClass = '\\App\\Services\\UserProvider\\Providers\\'.ucfirst($provider);

        if (!class_exists($providerClass)) {
            throw new UserProviderNotFound();
        }

        $valid = $providerClass::validate($accessToken);

        return $valid;
    }
}
