<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Likemusic\YandexFleetTaxiClient\Contracts\ClientInterface as YandexClientInterface;
use Likemusic\YandexFleetTaxiClient\Contracts\LanguageInterface;

class UpdateCarBrandsAndModels extends Command
{
    const FILE_PUBLIC_RELATIVE_NAME = 'js/data/car/brands.json';
    const DIR_PUBLIC_RELATIVE_NAME = 'js/data/car/models/';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cars:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate cars brands and models data by Yandex-provided data.';

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
     * @param YandexClientInterface $yandexClient
     * @param string $yandexLogin
     * @param string $yandexPassword
     * @param string $parkId
     */
    public function __construct(YandexClientInterface $yandexClient, string $yandexLogin, string $yandexPassword, string $parkId)
    {
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
        $brands = $this->generateBrands($yandexClient, $parkId);
//        $this->generateModelsForBrands($yandexClient, $brands);
    }

    private function generateBrands(YandexClientInterface $yandexClient, string $parkId)
    {
        $vehiclesCardData = $yandexClient->getVehiclesCardData($parkId);
        $brands = $this->getBrandsByVehiclesCardData($vehiclesCardData);
        $this->saveBrands($brands);

        return $brands;
    }

    private function saveBrands(array $brands)
    {
        $brandsFullFilename = $this->getBrandsFullFilename();
        $json = json_encode($brands);

        file_put_contents($brandsFullFilename, $json);
    }

    private function getBrandsFullFilename()
    {
        $publicPath = public_path();

        return $publicPath . DIRECTORY_SEPARATOR . self::FILE_PUBLIC_RELATIVE_NAME;
    }

    private function getBrandsByVehiclesCardData(array $vehiclesCardData)
    {
        $sourceCarBrands = $vehiclesCardData['data']['references']['car_brands'];

        return array_map([$this, 'getBrandName'], $sourceCarBrands);
    }

    private function getBrandName(array $brand)
    {
        return $brand['name'];
    }

    private function initYandexClient(YandexClientInterface $yandexClient, $login, $password)
    {
        $yandexClient->login($login, $password);
        $yandexClient->getDashboardPageData();
        $yandexClient->changeLanguage(LanguageInterface::RUSSIAN);
    }
}
