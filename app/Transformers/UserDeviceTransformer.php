<?php

namespace App\Transformers;

use App\Models\UserDevice;

class UserDeviceTransformer extends Transformer
{
    public function transform(UserDevice $device)
    {
        if (!is_null($device)) {
            return [
                'id'            => $device->id,
                'token'         => $device->token,
                'device_type'   => $device->device_type,
                'created_at'    => $device->created_at,
            ];
        }
    }
}
