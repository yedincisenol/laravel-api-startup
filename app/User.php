<?php

namespace App;

use App\Models\UserDevice;
use App\Models\UserProvider as UserProviderModel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use League\OAuth2\Server\Exception\OAuthServerException;
use UserProvider;
use Validator;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function device()
    {
        return $this->hasMany(UserDevice::class);
    }

    public function userProviderRequest(Request $request)
    {
        $this->validateUserProviderRequest($request);

        $user = self::query()->where('email', $request->get('email'))->first();

        if (!$user) {
            $user = self::create([
                'email'     => $request->get('email'),
                'name'      => $request->get('name'),
                'password'  => bcrypt(uniqid('pw').'!*-'),
            ]);
        }

        $provider = UserProviderModel::firstOrNew([
            'user_id'   => $user->id,
            'provider'  => $request->get('provider'),
        ]);

        $provider->access_token = $request->get('access_token');
        $provider->refresh_token = $request->get('refresh_token');
        $provider->expires_at = $request->get('expires_at');

        $provider->save();

        return $user;
    }

    private function validateUserProviderRequest($request)
    {
        $provider = $request->get('provider');

        if (!UserProvider::validate($provider, $request->get('access_token'))) {
            throw OauthServerException::accessDenied(trans('auth.token_not_verified', ['provider' => $provider]));
        }

        $validator = Validator::make($request->all(), [
            'email'         => 'required|email|max:255',
            'access_token'  => 'required|string',
            'refresh_token' => 'required|string',
            'expires_at'    => 'required|date',
        ]);

        if ($validator->fails()) {
            throw OAuthServerException::invalidRequest('expires_at, email, refresh_token');
        }
    }
}
