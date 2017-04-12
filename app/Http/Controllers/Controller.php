<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationException;
use App\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Validator;

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
}
