<?php

namespace Likemusic\YandexFleetTaxi\LeadMonitor\GoogleSpreadsheet\app\Console\Commands\UpdateCarBrandsAndModels;

use Likemusic\YandexFleetTaxiClient\Contracts\ClientInterface as YandexClientInterface;

class CarBrandModelsGenerator
{
    const DIR_PUBLIC_RELATIVE_NAME = 'js/data/car/models';

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

    private function storeModels(string $brandName, array $models)
    {
        $modelsFullFilename = $this->getModelsFullFilename($brandName);
        $json = json_encode($models);

        file_put_contents($modelsFullFilename, $json);
    }

    private function getModelsFullFilename(string $brandName)
    {
        $relativePath = self::DIR_PUBLIC_RELATIVE_NAME . DIRECTORY_SEPARATOR . $brandName . '.json';

        return public_path($relativePath);
    }

    private function getModelsByBrandName(YandexClientInterface $yandexClient, $brandName)
    {
        $sourceData =  $yandexClient->getVehiclesCardModels($brandName);
        $sourceModels = $this->getSourceModelsByData($sourceData);

        return $this->getResultModelsBySourceModels($sourceModels);
    }

    private function getResultModelsBySourceModels(array $sourceModels)
    {
        return array_map([$this, 'getModelName'], $sourceModels);
    }

    private function getModelName(array $model)
    {
        return $model['name'];
    }

    private function getSourceModelsByData($sourceData)
    {
        return $sourceData['data'];
    }
}
