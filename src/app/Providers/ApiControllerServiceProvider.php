<?php

namespace App\Providers;

use App\Http\Controllers\ApiController;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository as ConfigInterface;


class ApiControllerServiceProvider extends ServiceProvider
{
    /**
     * @var ConfigInterface
     */
    private $config;

    public function __construct(Application $app)
    {
        $this->config = $app->get(ConfigInterface::class);

        parent::__construct($app);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $config = $this->config;

        $yandexLogin = $config->get('yandex-fleet.login');
        $yandexPassword = $config->get('yandex-fleet.password');
        $parkId = $config->get('yandex-fleet.park_id');

        $driverDefaultPostDataValues = $config->get('yandex-fleet.default_post_data_values.driver');
        $carDefaultPostDataValues = $config->get('yandex-fleet.default_post_data_values.car');

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
