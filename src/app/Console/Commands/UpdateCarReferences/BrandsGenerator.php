<?php

namespace Likemusic\YandexFleetTaxi\LeadMonitor\GoogleSpreadsheet\app\Console\Commands\UpdateCarReferences;

use Likemusic\YandexFleetTaxiClient\Contracts\ClientInterface as YandexClientInterface;
use App\Helpers\FilenamesProvider;

class BrandsGenerator
{
    /**
     * @var FilenamesProvider
     */
    private $filenamesProvider;

    public function __construct(FilenamesProvider $filenamesProvider)
    {
        $this->filenamesProvider = $filenamesProvider;
    }

    public function generateBrands(YandexClientInterface $yandexClient, string $parkId)
    {
        $vehiclesCardData = $yandexClient->getVehiclesCardData($parkId);
        $brands = $this->getBrandsByVehiclesCardData($vehiclesCardData);
        $this->saveBrands($brands);

        return $brands;
    }

    private function getBrandsByVehiclesCardData(array $vehiclesCardData)
    {
        $sourceCarBrands = $vehiclesCardData['data']['references']['car_brands'];

        return array_map([$this, 'getBrandName'], $sourceCarBrands);
    }

    private function saveBrands(array $brands)
    {
        $brandsFullFilename = $this->getBrandsFullFilename();
        $json = json_encode($brands);

        file_put_contents($brandsFullFilename, $json);
    }

    private function getBrandsFullFilename()
    {
        return $this->filenamesProvider->getCarBrandsFullFilename();
    }

    private function getBrandName(array $brand)
    {
        return $brand['name'];
    }
}
