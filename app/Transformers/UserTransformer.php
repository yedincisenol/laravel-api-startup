<?php

namespace App\Transformers;

use App\User;

class UserTransformer extends Transformer
{
    public function transform(User $user)
    {
        return [
            'id'    => (int) $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ];
    }
}
