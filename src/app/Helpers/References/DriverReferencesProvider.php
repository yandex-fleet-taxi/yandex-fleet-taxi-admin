<?php

namespace App\Helpers\References;

class DriverReferencesProvider extends BaseReferencesProvider
{
    public function getKnownCountries()
    {
        $countriesFilename = $this->getCountriesFilename();
        $json = file_get_contents($countriesFilename);

        return $this->jsonDecode($json);
    }

    private function getCountriesFilename()
    {
        return $this->filenamesProvider->getDriverLicenseIssueCountriesFullFilename();
    }
}
