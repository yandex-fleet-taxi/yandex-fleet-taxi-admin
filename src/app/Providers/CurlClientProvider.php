<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Http\Client\Curl\Client as CurlClient;

class CurlClientProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $builder = $this->app->when(CurlClient::class);
        $curlOptions = config('yandex-fleet.curl_options');
        $builder->needs('$options')->give($curlOptions);
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
