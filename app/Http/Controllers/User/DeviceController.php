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
        $request->user()->device()->create(
            $request->all()
        );

        return $this->response->created();
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
     * @param $id
     *
     * @return \Dingo\Api\Http\Response|void
     */
    public function show(Request $request, $id)
    {
        $device = $request->user()->device()->where('id', $id)->first();

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
     * @param $id
     *
     * @return \Dingo\Api\Http\Response|void
     */
    public function destroy(Request $request, $id)
    {
        $device = $request->user()->device()->where('device_id', $id)->first();
        if (isset($device->id)) {
            $device->delete();

            return $this->response->noContent();
        }

        return $this->response->errorNotFound(trans('exception.device_not_found'));
    }
}
