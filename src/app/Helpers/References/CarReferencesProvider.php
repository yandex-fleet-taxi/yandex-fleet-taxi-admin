<?php

namespace App\Helpers\References;

class CarReferencesProvider extends BaseReferencesProvider
{
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

    private function getKnownBrandsFilename()
    {
        return $this->filenamesProvider->getCarBrandsFullFilename();
    }
}
