<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Notifications\EmailVerificationNotification;
use App\User;
use yedincisenol\UserProvider\Models\UserProvider;
use yedincisenol\UserProviderFacebook\FacebookTokenValidationRule;
use yedincisenol\UserProviderGoogle\GoogleTokenValidationRule;
use yedincisenol\UserProviderLinkedin\LinkedinTokenValidationRule;
use yedincisenol\UserProviderTwitter\TwitterTokenValidationRule;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

    /**
     * Manual register
     * @param RegisterRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function email(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->get('name'),
            'email'    => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'verification_code' =>  app('app\Http\Controllers\Controller')->getVerificationCode()
        ]);

        $user->notify(new EmailVerificationNotification());

        return $this->response->created();
    }

    /**
     * Login with Google
     * @param Request $request
     * @return mixed
     */
    public function withGoogle(Request $request)
    {
        $this->validate($request, [
            'access_token'  =>  ['required', new GoogleTokenValidationRule()],
            'google_id'     =>  'required',
            'name'          =>  'required'
        ]);

        $user = $this->getUser($request, $request->get('google_id'));
        $user->providers()->updateOrCreate([
            'provider'          =>  'google',
            'provider_user_id'  =>  $request->get('google_id'),
        ],[
            'access_token'      =>  $request->get('access_token'),
        ]);

        return $user;
    }


    /**
     * Login with Twitter
     * @param Request $request
     * @return mixed
     */
    public function withLinkedin(Request $request)
    {
        $this->validate($request, [
            'access_token'  =>  ['required', new LinkedinTokenValidationRule()],
            'linkedin_id'   =>  'required',
            'name'          =>  'required'
        ]);

        $user = $this->getUser($request, $request->get('linkedin_id'));
        $user->providers()->updateOrCreate([
            'provider'          =>  'twitter',
            'provider_user_id'  =>  $request->get('linkedin_id'),
        ],[
            'access_token'      =>  $request->get('access_token'),
        ]);

        return $user;
    }


    /**
     * Login with Twitter
     * @param Request $request
     * @return mixed
     */
    public function withTwitter(Request $request)
    {
        $this->validate($request, [
            'access_token'  =>  ['required', new TwitterTokenValidationRule()],
            'twitter_id'    =>  'required',
            'name'          =>  'required'
        ]);

        $user = $this->getUser($request, $request->get('twitter_id'));
        $user->providers()->updateOrCreate([
            'provider'          =>  'twitter',
            'provider_user_id'  =>  $request->get('twitter_id'),
        ],[
            'access_token'      =>  $request->get('access_token'),
        ]);

        return $user;
    }


    /**
     * Validate and login user by provider
     * @param Request $request
     * @return mixed
     */
    public function withFacebook(Request $request)
    {
        $this->validate($request, [
            'access_token' => ['required', new FacebookTokenValidationRule()],
            'facebook_id'  => 'required',
            'name'         => 'required',
        ]);

        $user = $this->getUser($request, $request->get('facebook_id'));

        $user->providers()->updateOrCreate([
            'provider'          =>  'facebook',
            'provider_user_id'  =>  $request->get('facebook_id'),
        ],[
            'access_token'      =>  $request->get('access_token'),
        ]);

        return $user;
    }

    /**
     * Get or create user by provider
     * @param $request
     * @param $id
     * @return mixed
     */
    private function getUser($request, $id)
    {
        $userProvider = UserProvider::query()
            ->where('provider_user_id', $id)
            ->first();

        if (!$userProvider || !$user = User::find($userProvider->user_id)) {
            $user = $this->createUser($request, $id);
        }

        return $user;
    }

    /**
     * Create user
     * @param $request
     * @param $id
     * @return mixed
     */
    private function createUser($request, $id)
    {
        $user = User::create([
            'name'          =>  $request->get('name'),
            'email'         =>  $request->get('email', 'temp-' . $id . '@yedincisenol.com'),
            'password'      =>  bcrypt($id),
            'picture_url'   =>  $request->get('picture_url'),
        ]);

        return $user;

    }
}