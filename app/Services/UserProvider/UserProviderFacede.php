<?php

namespace App\Services\UserProvider;

use Illuminate\Support\Facades\Facade;

class UserProviderFacede extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'UserProvider';
    }
}
