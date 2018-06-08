<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Transformers\ProviderTransformer;
use Illuminate\Http\Request;

class ConnectController extends Controller
{
    /**
     * List of connected providers
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        return $this->response->collection($request->user()->providers, new ProviderTransformer());
    }
}