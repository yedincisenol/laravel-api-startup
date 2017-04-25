<?php

namespace App\Models;

class UserProvider extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'user_providers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'provider', 'access_token', 'refresh_token', 'expires_at',
    ];

    /**
     * The rules to be applied to the data.
     *
     * @var array
     */
    protected $rules = [
        'provider'      => 'required|in:facebook,google',
        'access_token'  =>  'required|string',
        'refresh_token' =>  'required|string',
        'expires_at'    =>  'required|date',
        'user_id'       =>  'required|integer'
    ];

    /**
     * Get users of device.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user()
    {
        return $this->morphTo();
    }
}
