<?php

namespace Likemusic\YandexFleetTaxi\LeadMonitor\GoogleSpreadsheet\app\Console\Commands\UpdateDriverReferences;

use App\Helpers\FilenamesProvider;
use Likemusic\YandexFleetTaxiClient\Contracts\ClientInterface as YandexClientInterface;

class CitiesGenerator
{
    /**
     * @var FilenamesProvider
     */
    private $filenamesProvider;

    public function __construct(FilenamesProvider $filenamesProvider)
    {
        $this->filenamesProvider = $filenamesProvider;
    }

    public function generateItems(YandexClientInterface $yandexClient, string $parkId)
    {
        $yandexData = $yandexClient->getDriversCardData($parkId);
        $cities = $this->getItemsByYandexData($yandexData);
        sort($cities);

        $this->saveItems($cities);

        return $cities;
    }

    private function getItemsByYandexData(array $yandexData)
    {
        $countries = $yandexData['data']['references']['countries'];

        return array_map([$this, 'getNameRu'], $countries);
    }

    private function saveItems(array $brands)
    {
        $fullFilename = $this->getFullFilename();
        $json = json_encode($brands);

        file_put_contents($fullFilename, $json);
    }

    private function getFullFilename()
    {
        return $this->filenamesProvider->getDriverLicenseIssueCountriesFullFilename();
    }

    private function getNameRu(array $country)
    {
        return $country['name_ru'];
    }
}
