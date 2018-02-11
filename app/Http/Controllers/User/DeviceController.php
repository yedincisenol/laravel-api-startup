<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Transformers\UserDeviceTransformer;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Store new device.
     *
     * @param Request $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $device = $request->user()->device()->create(
            $request->all()
        );

        return $this->response->item($device, new UserDeviceTransformer());
    }

    /**
     * List of users devices.
     *
     * @param Request $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        return $this->response->paginator(
            $request->user()->device()->paginate(10), new UserDeviceTransformer()
        );
    }

    /**
     * Get users device details.
     *
     * @param Request $request
     * @param $token
     * @return \Dingo\Api\Http\Response|void
     * @internal param $id
     *
     */
    public function show(Request $request, $token)
    {
        $device = $request->user()->device()->where('token', $token)->first();

        if (!isset($device->id)) {
            return $this->response->errorNotFound(trans('exception.device_not_found'));
        }

        return $this->response->item(
            $device, new UserDeviceTransformer()
        );
    }

    /**
     * Remove device.
     *
     * @param Request $request
     * @param $token
     * @return \Dingo\Api\Http\Response|void
     * @internal param $id
     *
     */
    public function destroy(Request $request, $token)
    {
        $device = $request->user()->device()->where('token', $token)->first();
        if (isset($device->id)) {
            $device->delete();

            return $this->response->noContent();
        }

        return $this->response->errorNotFound(trans('exception.device_not_found'));
    }
}
