<?php

namespace App\Helpers;

class FilenamesProvider
{
    const FILE_PUBLIC_RELATIVE_NAME = 'js/data/car/brands.json';
    const DIR_PUBLIC_RELATIVE_NAME = 'js/data/car/models';

    public function getBrandsFullFilename()
    {
        return public_path(self::FILE_PUBLIC_RELATIVE_NAME);
    }

    public function getBrandModelsFullFilename(string $brandName)
    {
        $relativePath = self::DIR_PUBLIC_RELATIVE_NAME . DIRECTORY_SEPARATOR . $brandName . '.json';

        return public_path($relativePath);
    }
}
