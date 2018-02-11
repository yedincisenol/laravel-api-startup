<?php

namespace App\Models;

class UserDevice extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'user_devices';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'device_type', 'token',
    ];

    /**
     * The rules to be applied to the data.
     *
     * @var array
     */
    protected $rules = [
        'device_type' => 'required|in:ios,android,web',
        'token'       => 'required|unique:user_devices,token,:id,id',
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
