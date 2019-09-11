<?php

namespace App\Providers;

use Http\Client\Curl\Client as CurlClient;
use Http\Client\HttpClient;
use Illuminate\Support\ServiceProvider;

class CurlHttpClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(HttpClient::class, CurlClient::class);
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
