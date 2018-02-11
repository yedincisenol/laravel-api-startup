<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\MeUpdateRequest;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * Show logged-in user
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function show(Request $request)
    {
        return $this->response->item($request->user(), new UserTransformer());
    }

    /**
     * Update profile infirmation
     * @param MeUpdateRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(MeUpdateRequest $request)
    {
        $request->user()->update($request->except(['role']));

        return $this->response->item($request->user(), new UserTransformer());
    }
}