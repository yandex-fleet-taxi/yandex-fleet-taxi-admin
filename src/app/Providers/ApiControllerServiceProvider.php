<?php

namespace App\Providers;

use App\Http\Controllers\ApiController;
use Illuminate\Support\ServiceProvider;

class ApiControllerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $yandexLogin = config('yandex-fleet.login');
        $yandexPassword = config('yandex-fleet.password');
        $parkId = config('yandex-fleet.park_id');

        $apiControllerBuilder = $this->app->when(ApiController::class);

        $apiControllerBuilder->needs('$parkId')->give($parkId);
        $apiControllerBuilder->needs('$yandexLogin')->give($yandexLogin);
        $apiControllerBuilder->needs('$yandexPassword')->give($yandexPassword);
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
