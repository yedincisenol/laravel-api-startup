<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConnectRequest;
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

    /**
     * @param ConnectRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(ConnectRequest $request)
    {
        $provider = $request->user()->providers()->updateOrCreate([
            'provider'  =>  $request->get('provider')
        ], $request->all());

        return $this->response->item($provider, new ProviderTransformer());
    }

    /**
     * @param ConnectRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(ConnectRequest $request, $id)
    {
        $provider = $request->user()->providers()->find($id);
        if (!$provider) {
            abort(404, trans('user.connection_not_found'));
        }

        $provider->update($request->all());

        return $this->response->item($provider, new ProviderTransformer());
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Dingo\Api\Http\Response|void
     */
    public function destroy(Request $request, $id)
    {
        $provider = $request->user()->providers()->where('id', $id)->first();
        if (!$provider) {
            return $this->response->errorNotFound(trans('user.provider_not_found'));
        }

        $provider->delete();

        return $this->response->noContent();
    }
}
