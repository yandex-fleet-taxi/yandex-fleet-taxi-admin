<?php

namespace App\Helpers;

class CarHelper
{
    private $filenamesProvider;

    public function __construct(FilenamesProvider $filenamesProvider)
    {
        $this->filenamesProvider = $filenamesProvider;
    }

    public function getKnownBrands()
    {
        $brandsFullFilename = $this->getKnownBrandsFilename();
        $json = file_get_contents($brandsFullFilename);

        return $this->jsonDecode($json);
    }

    public function getKnownBrandModels(string $brandName)
    {
        $modelsFilename = $this->getKnownBrandModelsFilename($brandName);
        $json = file_get_contents($modelsFilename);

        return $this->jsonDecode($json);
    }

    private function getKnownBrandModelsFilename(string $brandName)
    {
        return $this->filenamesProvider->getCarBrandModelsFullFilename($brandName);
    }

    private function jsonDecode(string $json)
    {
        return json_decode($json, true);
    }

    private function getKnownBrandsFilename()
    {
        return $this->filenamesProvider->getCarBrandsFullFilename();
    }
}
