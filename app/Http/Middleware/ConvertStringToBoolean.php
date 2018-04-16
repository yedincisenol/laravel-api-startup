<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class ConvertStringToBoolean extends TransformsRequest
{
    /**
     * Convert true/false string to boolean.
     *
     * @param $key
     * @param $value
     *
     * @return bool
     */
    protected function transform($key, $value)
    {
        $lowered = strtolower($value);

        if ($lowered === 'true') {
            return true;
        }

        if ($lowered === 'false') {
            return false;
        }

        if ($lowered === 'null') {
            return;
        }

        return $value;
    }
}
