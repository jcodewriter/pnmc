<?php

namespace App\Providers;

use App\Services\Exchanges\Idex;
use Illuminate\Support\ServiceProvider;

class ExchangeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->singleton('Exchanges\ExampleSecretExchange', function () {
//            return new \App\Services\Exchanges\ExampleSecretExchange('clientId', 'clientSecret');
//        });
//
//        $this->app->singleton('Exchanges\ExampleKeyExchange', function () {
//            return new \App\Services\Exchanges\ExampleKeyExchange('key');
//        });

        $this->app->singleton(Idex::class, function () {
            return new \App\Services\Exchanges\Idex(config('services.exchanges.idex'));
        });

        $this->app->singleton('ExchangeData', function () {
            return new \App\Services\Exchanges();
        });
    }
}
