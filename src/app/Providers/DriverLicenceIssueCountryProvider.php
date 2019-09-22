<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Likemusic\YandexFleetTaxi\FrontendData\ToYandexClientPostDataConverters\Converter\ToCreateDriver\DriverLicenceIssueCountry;
use App\Helpers\References\DriverReferencesProvider;

class DriverLicenceIssueCountryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /** @var DriverReferencesProvider $countriesProvider */
        $countriesProvider = $this->app->get(DriverReferencesProvider::class);
        $countries = $countriesProvider->getKnownCountries();

        $this->app
            ->when(DriverLicenceIssueCountry::class)
            ->needs('$countries')
            ->give($countries);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
