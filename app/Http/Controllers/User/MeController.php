<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\MeUpdateRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MeController extends Controller
{
    /**
     * Show logged-in user.
     *
     * @param Request $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function show(Request $request)
    {
        return $this->response->item($request->user(), new UserTransformer());
    }

    /**
     * Update profile infirmation.
     *
     * @param MeUpdateRequest $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function update(MeUpdateRequest $request)
    {
        $request->user()->update($request->except(['role']));

        return $this->response->item($request->user(), new UserTransformer());
    }


    /**
     * Update password
     * @param PasswordUpdateRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function passwordUpdate(PasswordUpdateRequest $request)
    {

        if (!Hash::check($request->get('current_password'), $request->user()->password)) {
            abort(422, trans('passwords.wrong_password'));
        }

        $password = $request->get('password');

        $request->user()->update([
            'password' => bcrypt($password)
        ]);

        return $this->response->item($request->user(), new UserTransformer());
    }
}
