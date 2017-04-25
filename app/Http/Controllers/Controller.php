<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationException;
use App\Models\UserProvider as UserProviderModel;
use App\User;
use Carbon\Carbon;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Validator;
use UserProvider;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;

    public function register(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|min:6', ]);

        if ($valid->fails()) {
            throw new ValidationException($valid->errors());
        }

        User::create([
            'name'     => $request->get('name'),
            'email'    => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);

        return $this->response->created();
    }

    /**
     * @param Request $request
     */
    private function validateLoginWithProvider(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name'          => 'required|max:255',
            'email'         => 'required|email|max:255',
            'access_token'  =>  'required|string',
            'refresh_token' =>  'required|string',
            'expires_at'    =>  'required|date',
        ]);

        if ($valid->fails()) {
            throw new ValidationException($valid->errors());
        }

    }

    public function loginWithProvider($provider, Request $request)
    {
        $this->validateLoginWithProvider($request);

        if(! UserProvider::validate($provider, $request->get('access_token'))) {
            return $this->response->error( trans('Given access token not verified by ' . $provider), 422 );
        }

        $user = User::query()->where('email', $request->get('email'))->first();

        if(! $user) {
            $user = User::create([
                'email'     =>  $request->get('email'),
                'name'      =>  $request->get('name'),
                'password'  =>  bcrypt(uniqid("pw") . "!*-")
            ]);
        }

        $provider   =   UserProviderModel::firstOrNew([
            'user_id'   =>  $user->id,
            'provider'  =>  $provider
        ]);

        $provider->access_token =   $request->get('access_token');
        $provider->refresh_token=   $request->get('refresh_token');
        $provider->expires_at   =   $request->get('expires_at');

        $provider->save();

        $token = $user->createToken("Token", ["*"]);

        $response  =   array(
            'token_type'    =>  'Bearer',
            'expires_in'    =>  Carbon::now()->addYear(100)->timestamp,
            'access_token'  =>  $token->accessToken,
            'refresh_token' =>  null
        );
        return $this->response->array($response);

    }
}
