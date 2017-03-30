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
                'device_id'     => $device->device_id,
                'device_type'   => $device->device_type,
                'created_at'    => $device->created_at,
            ];
        }
    }
}
