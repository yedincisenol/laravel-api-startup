<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Setting;

class SettingController extends Controller
{
    private $availableSettings;

    public function __construct()
    {
        $this->availableSettings = explode(',', config('project.available_settings'));
    }

    /**
     * Store or update setting.
     *
     * @param Request $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function storeOrUpdate(Request $request)
    {
        Setting::setExtraColumns([
            'user_id' => $request->user()->id,
        ]);
        $settings = $request->only($this->availableSettings);

        foreach ($settings as $key => $value) {
            if ($value !== false) {
                Setting::set($key, $value);
            }
        }

        Setting::save();

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
        Setting::setExtraColumns([
            'user_id' => $request->user()->id,
        ]);

        $response['data'] = Setting::all();

        return $this->response->array($response);
    }
}
