<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Likemusic\YandexFleetTaxi\LeadMonitor\GoogleSpreadsheet\app\Console\Commands\UpdateDriverReferences\CitiesGenerator;
use Likemusic\YandexFleetTaxiClient\Contracts\ClientInterface as YandexClientInterface;
use Likemusic\YandexFleetTaxiClient\Contracts\LanguageInterface;

class UpdateDriverReferences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fleet:driver-references:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate driver references data (license issue countries) by Yandex-provided data.';

    /**
     * @var CitiesGenerator
     */
    private $driverLicenseIssueCitiesGenerator;

    /**
     * @var YandexClientInterface
     */
    private $yandexClient;

    /**
     * @var string
     */
    private $yandexLogin;

    /**
     * @var string
     */
    private $yandexPassword;

    /**
     * @var string
     */
    private $parkId;

    /**
     * Create a new command instance.
     *
     * @param CitiesGenerator $driverLicenseIssueCitiesGenerator
     * @param YandexClientInterface $yandexClient
     * @param string $yandexLogin
     * @param string $yandexPassword
     * @param string $parkId
     */
    public function __construct(
        CitiesGenerator $driverLicenseIssueCitiesGenerator,
        YandexClientInterface $yandexClient,
        string $yandexLogin,
        string $yandexPassword,
        string $parkId
    ) {
        $this->driverLicenseIssueCitiesGenerator = $driverLicenseIssueCitiesGenerator;
        $this->yandexClient = $yandexClient;
        $this->yandexLogin = $yandexLogin;
        $this->yandexPassword = $yandexPassword;
        $this->parkId = $parkId;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $yandexClient = $this->yandexClient;
        $parkId = $this->parkId;//todo: проверить нужен ли действительно parkId, или можно получить данные и без него

        $this->initYandexClient($yandexClient, $this->yandexLogin, $this->yandexPassword);
        $this->generateCities($yandexClient, $parkId);

        return true;
    }

    private function initYandexClient(YandexClientInterface $yandexClient, $login, $password)
    {
        $yandexClient->login($login, $password);
        $yandexClient->getDashboardPageData();
        $yandexClient->changeLanguage(LanguageInterface::RUSSIAN);
    }

    private function generateCities(YandexClientInterface $yandexClient, string $parkId)
    {
        return $this->driverLicenseIssueCitiesGenerator->generateItems($yandexClient, $parkId);
    }
}
