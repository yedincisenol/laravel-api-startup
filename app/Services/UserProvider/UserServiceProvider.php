<?php

namespace App\Services\UserProvider;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('UserProvider', function ($app) {
            return new UserProviderClient(
                $app['config']['services']['user_provider']
            );
        });
    }

    public function provides()
    {
        return ['UserProvider'];
    }
}
