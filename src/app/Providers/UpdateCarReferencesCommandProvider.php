<?php

namespace App\Providers;

use App\Console\Commands\UpdateCarReferences as UpdateCarBrandsAndModelsCommand;
use Illuminate\Contracts\Config\Repository as ConfigInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class UpdateCarReferencesCommandProvider extends ServiceProvider
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

        $builder = $this->app->when(UpdateCarBrandsAndModelsCommand::class);

        $builder->needs('$yandexLogin')->give($yandexLogin);
        $builder->needs('$yandexPassword')->give($yandexPassword);
        $builder->needs('$parkId')->give($parkId);
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
