<?php

namespace App\Transformers;

use yedincisenol\UserProvider\Models\UserProvider;

class ProviderTransformer extends Transformer
{
    public function transform(UserProvider $provider)
    {
        return [
            'id'                => $provider->id,
            'provider'          => $provider->provider,
            'provider_user_id'  => $provider->provider_user_id,
            'access_token'      => $provider->access_token,
            'refresh_token'     => $provider->refresh_token,
        ];
    }
}
