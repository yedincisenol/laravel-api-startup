<?php

namespace App;

use App\Models\UserDevice;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use yedincisenol\UserProvider\Models\UserProvider;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'username', 'is_active', 'picture_url', 'verification_code', 'deleted_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function providers()
    {
        return $this->hasMany(UserProvider::class);
    }

    /**
     * Login with email or mobile.
     *
     * @param $identifier
     *
     * @return mixed
     */
    public function findForPassport($identifier)
    {
        return $this->where(function ($query) use ($identifier) {
            $query->where('email', $identifier)
                ->orWhere('username', $identifier);
        })->first();
    }

    public function getIsAdminAttribute()
    {
        if ($this->attributes['role'] == 'user') {
            return false;
        }

        return true;
    }
}
