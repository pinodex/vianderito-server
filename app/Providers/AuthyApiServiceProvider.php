<?php

namespace App\Providers;

use Authy\AuthyApi;
use Illuminate\Support\ServiceProvider;

class AuthyApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AuthyApi::class, function ($app) {
            $key = env('AUTHY_API_KEY');
            
            return new AuthyApi($key);
        });
    }
}
