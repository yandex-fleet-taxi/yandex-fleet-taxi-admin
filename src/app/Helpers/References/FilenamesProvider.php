<?php

namespace App\Helpers\References;

class FilenamesProvider
{
    // CAR
    const CAR_COLORS_FILENAME = 'js/data/car/colors.json';
    const CAR_BRANDS_FILENAME = 'js/data/car/brands.json';
    const CAR_BRAND_MODELS_DIR = 'js/data/car/models';

    // DRIVER
    const DRIVER_LICENSE_ISSUE_COUNTRY_FILENAME = 'js/data/driver/license/countries.json';

    public function getCarBrandsFullFilename()
    {
        return public_path(self::CAR_BRANDS_FILENAME);
    }

    public function getCarColorsFullFilename()
    {
        return public_path(self::CAR_COLORS_FILENAME);
    }

    public function getCarBrandModelsFullFilename(string $brandName)
    {
        $relativePath = self::CAR_BRAND_MODELS_DIR . DIRECTORY_SEPARATOR . $brandName . '.json';

        return public_path($relativePath);
    }

    public function getDriverLicenseIssueCountriesFullFilename()
    {
        return public_path(self::DRIVER_LICENSE_ISSUE_COUNTRY_FILENAME);
    }
}
