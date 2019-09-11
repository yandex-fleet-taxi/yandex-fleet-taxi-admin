<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Likemusic\YandexFleetTaxiClient\Contracts\ClientInterface;
use Likemusic\YandexFleetTaxiClient\Client;

class YandexFleetClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ClientInterface::class, Client::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
