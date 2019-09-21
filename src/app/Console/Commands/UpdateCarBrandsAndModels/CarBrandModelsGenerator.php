<?php

namespace Likemusic\YandexFleetTaxi\LeadMonitor\GoogleSpreadsheet\app\Console\Commands\UpdateCarBrandsAndModels;

use App\Helpers\FilenamesProvider;
use Likemusic\YandexFleetTaxiClient\Contracts\ClientInterface as YandexClientInterface;

class CarBrandModelsGenerator
{
    /**
     * @var FilenamesProvider
     */
    private $filenamesProvider;

    public function __construct(FilenamesProvider $filenamesProvider)
    {
        $this->filenamesProvider = $filenamesProvider;
    }

    public function generateBrandsModels(YandexClientInterface $yandexClient, array $brandNames)
    {
        foreach ($brandNames as $brandName) {
            $this->generateBrandModels($yandexClient, $brandName);
        }
    }

    private function generateBrandModels(YandexClientInterface $yandexClient, $brandName)
    {
        $models = $this->getModelsByBrandName($yandexClient, $brandName);
        $this->storeModels($brandName, $models);

        return $models;
    }

    private function getModelsByBrandName(YandexClientInterface $yandexClient, $brandName)
    {
        $sourceData =  $yandexClient->getVehiclesCardModels($brandName);
        $sourceModels = $this->getSourceModelsByData($sourceData);

        return $this->getResultModelsBySourceModels($sourceModels);
    }

    private function getSourceModelsByData($sourceData)
    {
        return $sourceData['data'];
    }

    private function getResultModelsBySourceModels(array $sourceModels)
    {
        return array_map([$this, 'getModelName'], $sourceModels);
    }

    private function storeModels(string $brandName, array $models)
    {
        $modelsFullFilename = $this->getModelsFullFilename($brandName);
        $json = json_encode($models);

        file_put_contents($modelsFullFilename, $json);
    }

    private function getModelsFullFilename(string $brandName)
    {
        return $this->filenamesProvider->getBrandModelsFullFilename($brandName);
    }

    private function getModelName(array $model)
    {
        return $model['name'];
    }
}
