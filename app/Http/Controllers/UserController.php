<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationException;
use App\Transformers\UserTransformer;
use App\User;
use Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * List All Users
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        return $this->response->paginator(User::paginate($request->get('limit')), new UserTransformer());
    }

    /**
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        return $this->response->item($this->find($id), new UserTransformer());
    }

    /**
     * Delete user
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function delete($id)
    {
        $user = $this->find($id);
        $user->delete();

        return $this->response->noContent();
    }

    /**
     * Update an user
     * @param Request $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->find($id);
        $validator = Validator::make($request->all(), [
            'email'       => 'unique:users',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }

        $user->update($request->all());

        return $this->response->item($user, new UserTransformer());
    }

    /**
     * @param $id
     */
    public function find($id)
    {
        if (!$user = User::find($id)) {
            return abort(404, trans('User not found'));
        }

        return $user;
    }
}