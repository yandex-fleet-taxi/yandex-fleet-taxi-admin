<?php

namespace App\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository as ConfigInterface;
use App\Http\Middleware\Cors as CorsMiddleware;

class CorsMiddlewareProvider extends ServiceProvider
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * CorsMiddlewareProvider constructor.
     * @param Application $app
     */
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
        $host = $config->get('yandex-fleet.cors_host');

        $this->app->when(CorsMiddleware::class)->needs('$host')->give($host);
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
