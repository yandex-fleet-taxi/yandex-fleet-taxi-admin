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

        $driverDefaultPostDataValues = config('yandex-fleet.default_post_data_values.driver');
        $carDefaultPostDataValues = config('yandex-fleet.default_post_data_values.car');

        $apiControllerBuilder = $this->app->when(ApiController::class);

        $apiControllerBuilder->needs('$parkId')->give($parkId);
        $apiControllerBuilder->needs('$yandexLogin')->give($yandexLogin);
        $apiControllerBuilder->needs('$yandexPassword')->give($yandexPassword);
        $apiControllerBuilder->needs('$defaultDriverPostData')->give($driverDefaultPostDataValues);
        $apiControllerBuilder->needs('$defaultCarPostData')->give($carDefaultPostDataValues);
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
