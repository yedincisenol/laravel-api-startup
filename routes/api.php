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
    ], function ($api) {
        $api->group(['middleware'   => ['auth:api', 'scope:manage-devices'],
                     'prefix'       => 'self', 'namespace'=> 'User', ],
            function ($api) {
                $api->get('me', 'MeController@show', ['middleware' => ['scope:show-user']]);
                $api->put('me', 'MeController@update', ['middleware' => ['scope:edit-user']]);
                $api->put('password', 'MeController@passwordUpdate', ['middleware' => ['scope:update-password']]);
                $api->resource('device', 'DeviceController', ['middleware'   => ['scope:manage-devices']]);
                $api->get('setting', 'SettingController@index');
                $api->post('setting', 'SettingController@storeOrUpdate', ['middleware' => ['scope:manage-settings']]);
            });

        $api->post('register', 'Controller@register');
        $api->post('notification', 'NotificationController@send');
        $api->group(['middleware' => ['auth:api'], 'prefix' => 'user'], function ($api) {
            $api->get('/', 'UserController@index')->middleware(['admin']);
            $api->get('/{id}', 'UserController@show')->middleware(['admin']);
            $api->delete('/{id}', 'UserController@delete')->middleware(['admin']);
            $api->put('/{id}', 'UserController@update')->middleware(['admin']);
        });
    });
