<?php

namespace App\Console\Commands;

use App\Console\Commands\UpdateCarReferences\BrandModelsGenerator;
use App\Console\Commands\UpdateCarReferences\BrandsGenerator;
use App\Console\Commands\UpdateCarReferences\ColorsGenerator;
use Illuminate\Console\Command;
use Likemusic\YandexFleetTaxiClient\Contracts\ClientInterface as YandexClientInterface;
use Likemusic\YandexFleetTaxiClient\Contracts\LanguageInterface;

class UpdateCarReferences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fleet:car-references:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate car references (brands and models) data by Yandex-provided data.';

    /**
     * @var BrandsGenerator
     */
    private $brandsGenerator;

    /**
     * @var BrandModelsGenerator
     */
    private $brandModelsGenerator;

    /**
     * @var ColorsGenerator
     */
    private $colorsGenerator;

    /**
     * @var YandexClientInterface
     */
    private $yandexClient;

    /**
     * @var string
     */
    private $yandexLogin;

    /**
     * @var string
     */
    private $yandexPassword;

    /**
     * @var string
     */
    private $parkId;

    /**
     * Create a new command instance.
     *
     * @param ColorsGenerator $colorsGenerator
     * @param BrandsGenerator $carBrandsGenerator
     * @param BrandModelsGenerator $carBrandModelsGenerator
     * @param YandexClientInterface $yandexClient
     * @param string $yandexLogin
     * @param string $yandexPassword
     * @param string $parkId
     */
    public function __construct(
        ColorsGenerator $colorsGenerator,
        BrandsGenerator $carBrandsGenerator,
        BrandModelsGenerator $carBrandModelsGenerator,
        YandexClientInterface $yandexClient,
        string $yandexLogin,
        string $yandexPassword,
        string $parkId
    )
    {
        $this->colorsGenerator = $colorsGenerator;
        $this->brandsGenerator = $carBrandsGenerator;
        $this->brandModelsGenerator = $carBrandModelsGenerator;
        $this->yandexClient = $yandexClient;
        $this->yandexLogin = $yandexLogin;
        $this->yandexPassword = $yandexPassword;
        $this->parkId = $parkId;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $yandexClient = $this->yandexClient;
        $parkId = $this->parkId;//todo: проверить нужен ли действительно parkId, или можно получить данные и без него

        $this->initYandexClient($yandexClient, $this->yandexLogin, $this->yandexPassword);

        $vehiclesCardData = $yandexClient->getVehiclesCardData($parkId);

        $this->generateColors($vehiclesCardData);

        $brands = $this->generateBrands($vehiclesCardData);
        $this->generateModelsForBrands($yandexClient, $brands);

        return true;
    }

    private function initYandexClient(YandexClientInterface $yandexClient, $login, $password)
    {
        $yandexClient->login($login, $password);
        $yandexClient->getDashboardPageData();
        $yandexClient->changeLanguage(LanguageInterface::RUSSIAN);
    }

    private function generateColors(array $vehiclesCardData)
    {
        return $this->colorsGenerator->generate($vehiclesCardData);
    }

    private function generateBrands(array $vehiclesCardData)
    {
        return $this->brandsGenerator->generate($vehiclesCardData);
    }

    private function generateModelsForBrands($yandexClient, $brands)
    {
        $this->brandModelsGenerator->generateBrandsModels($yandexClient, $brands);
    }
}
