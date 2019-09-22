<?php

namespace App\Helpers\References;

abstract class BaseReferencesProvider
{
    protected $filenamesProvider;

    public function __construct(FilenamesProvider $filenamesProvider)
    {
        $this->filenamesProvider = $filenamesProvider;
    }

    protected function jsonDecode(string $json)
    {
        return json_decode($json, true);
    }
}
