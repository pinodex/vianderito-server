<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Braintree\Gateway;

class BraintreeServiceProvider extends ServiceProvider
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
        $this->app->singleton(Gateway::class, function ($app) {
            return new Gateway([
                'environment' => config('services.braintree.environment'),
                'merchantId' => config('services.braintree.id'),
                'publicKey' => config('services.braintree.public_key'),
                'privateKey' => config('services.braintree.private_key')
            ]);
        });
    }
}
