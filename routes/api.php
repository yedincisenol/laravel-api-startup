<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1.0', [
        'middleware' => ['api.throttle', 'client'],
        'limit'      => 200,
        'expires'    => 5,
        'namespace'  => 'App\Http\Controllers',
        'prefix'     => 'v1.0',
    ], function ($api) {
        $api->group(['middleware'   => ['auth:api', 'scope:manage-devices'],
                     'prefix'       => 'self', 'namespace'=> 'User', ],
            function ($api) {
                $api->resource('device', 'DeviceController', ['middleware'   => ['scope:manage-devices']]);
                $api->get('setting', 'SettingController@index');
                $api->post('setting', 'SettingController@storeOrUpdate', ['middleware' => ['scope:manage-settings']]);
            });

        $api->post('register', 'Controller@register');
    });
