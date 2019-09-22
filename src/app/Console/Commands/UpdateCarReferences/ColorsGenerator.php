<?php

namespace App\Console\Commands\UpdateCarReferences;

class ColorsGenerator extends BaseDataBasedGenerator
{
    protected function getItemsByData(array $data)
    {
        $colors = $data['data']['references']['car_colors'];

        $items = array_map([$this, 'getName'], $colors);
        sort($items);

        return $items;
    }

    protected function getItemsFullFilename()
    {
        return $this->filenamesProvider->getCarColorsFullFilename();
    }

    private function getName(array $brand)
    {
        return $brand['name'];
    }
}
