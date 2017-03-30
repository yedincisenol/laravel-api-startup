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
        'user_id', 'device_type', 'device_id',
    ];

    /**
     * The rules to be applied to the data.
     *
     * @var array
     */
    protected $rules = [
        'device_type' => 'required|in:ios,android,web',
        'device_id'   => 'required|unique:user_devices,device_id,:id,id',
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
