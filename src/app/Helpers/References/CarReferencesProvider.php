<?php

namespace App\Helpers\References;

class CarReferencesProvider extends BaseReferencesProvider
{
    public function getKnownColors()
    {
        $filename = $this->getKnownColorsFilename();

        return $this->getJsonDecodedFileContents($filename);
    }

    private function getKnownColorsFilename(): string
    {
        return $this->filenamesProvider->getCarColorsFullFilename();
    }

    private function getJsonDecodedFileContents($filename)
    {
        $json = file_get_contents($filename);

        return $this->jsonDecode($json);
    }

    public function getKnownBrands()
    {
        $filename = $this->getKnownBrandsFilename();

        return $this->getJsonDecodedFileContents($filename);
    }

    private function getKnownBrandsFilename()
    {
        return $this->filenamesProvider->getCarBrandsFullFilename();
    }

    public function getKnownBrandModels(string $brandName)
    {
        $filename = $this->getKnownBrandModelsFilename($brandName);

        return $this->getJsonDecodedFileContents($filename);
    }

    private function getKnownBrandModelsFilename(string $brandName)
    {
        return $this->filenamesProvider->getCarBrandModelsFullFilename($brandName);
    }
}
