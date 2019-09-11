<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Psr7FactoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;
        $app->instance(RequestFactoryInterface::class, Psr17FactoryDiscovery::findRequestFactory());
        $app->instance(StreamFactoryInterface::class, Psr17FactoryDiscovery::findStreamFactory());
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
