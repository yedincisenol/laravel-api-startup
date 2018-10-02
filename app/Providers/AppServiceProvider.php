<?php

namespace App\Providers;

use App\Http\Controllers\AccessTokenController;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('\\' . \Laravel\Passport\Http\Controllers\AccessTokenController::class, AccessTokenController::class);
    }
}
