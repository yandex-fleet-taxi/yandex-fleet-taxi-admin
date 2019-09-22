<?php

namespace App\Console\Commands\UpdateCarReferences;

class BrandsGenerator extends BaseDataBasedGenerator
{
    protected function getItemsByData(array $data)
    {
        $sourceCarBrands = $data['data']['references']['car_brands'];

        return array_map([$this, 'getName'], $sourceCarBrands);
    }

    protected function getItemsFullFilename()
    {
        return $this->filenamesProvider->getCarBrandsFullFilename();
    }

    private function getName(array $brand)
    {
        return $brand['name'];
    }
}
