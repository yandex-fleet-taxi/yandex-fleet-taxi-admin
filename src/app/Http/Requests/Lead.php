<?php

namespace App\Http\Requests;

use App\Helpers\References\CarReferencesProvider;
use App\Helpers\References\DriverReferencesProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Likemusic\YandexFleetTaxi\FrontendData\Contracts\CarInterface;
use Likemusic\YandexFleetTaxi\FrontendData\Contracts\DriverInterface;
use Likemusic\YandexFleetTaxi\FrontendData\Contracts\DriverLicenseInterface;

class Lead extends FormRequest
{
    const MIN_CAR_ISSUE_YEAR = 1984;

    /**
     * @var CarReferencesProvider
     */
    private $carHelper;

    /**
     * @var DriverReferencesProvider
     */
    private $driverReferencesProvider;

    public function __construct(
        CarReferencesProvider $carHelper,
        DriverReferencesProvider $driverReferencesProvider,
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    )
    {
        $this->carHelper = $carHelper;
        $this->driverReferencesProvider = $driverReferencesProvider;

        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @see https://docs.google.com/spreadsheets/d/1QPisHDS7YYXGf5kcqXhvav2fDIBGZoAbOhethOog2ZI/edit#gid=1479188633
     * @return array
     */
    public function rules()
    {
        $required = 'required';

        return [
            // Driver
            DriverInterface::FIRST_NAME => $required,
            DriverInterface::LAST_NAME => $required,
            DriverInterface::WORK_PHONE => $required,
            DriverInterface::BIRTH_DATE => 'date|before_or_equal:-18 years|before:licence_issue_date',

            // Driver License
            DriverLicenseInterface::ISSUE_COUNTRY => $this->getDriverLicenseIssueCountryValidation(),
            DriverLicenseInterface::EXPIRATION_DATE => 'required|after:licence_issue_date',
            DriverLicenseInterface::ISSUE_DATE => 'required|after:driver_birth_date',
            DriverLicenseInterface::NUMBER => $required,

            // Car
            CarInterface::BRAND => $this->getCarBrandValidation(),
            CarInterface::COLOR => $this->getCarColorValidation(),
            CarInterface::MODEL => $required,
            CarInterface::NUMBER => $required,
            CarInterface::REGISTRATION => $required,
            CarInterface::VIN => "required|size:17",
            CarInterface::ISSUE_YEAR => $this->getCarIssueYearValidation(),
        ];
    }

    private function getCarColorValidation()
    {
        $knownCarColors = $this->getKnownCarColors();

        return [
            'required',
            Rule::in($knownCarColors),
        ];
    }

    private function getKnownCarColors()
    {
        return $this->carHelper->getKnownColors();
    }

    private function getCarIssueYearValidation()
    {
        $minYear = self::MIN_CAR_ISSUE_YEAR;
        $maxYear = date('Y');

        return "required|integer|between:{$minYear},{$maxYear}";
    }

    /**
     * @return array
     */
    private function getDriverLicenseIssueCountryValidation()
    {
        $knownIssueCountries = $this->getKnownDriverLicenseIssueCountries();

        return [
            'required',
            Rule::in($knownIssueCountries),
        ];
    }

    /**
     * @return array
     */
    private function getKnownDriverLicenseIssueCountries()
    {
        return $this->driverReferencesProvider->getKnownCountries();
    }

    private function getCarBrandValidation()
    {
        $knownCarBrands = $this->getKnownCarBrands();

        return [
            'required',
            Rule::in($knownCarBrands),
        ];
    }

    private function getKnownCarBrands()
    {
        return $this->carHelper->getKnownBrands();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            // Driver
            DriverInterface::FIRST_NAME => '"Имя"',
            DriverInterface::LAST_NAME => '"Фамилия"',
            DriverInterface::MIDDLE_NAME => '"Отчество"',
            DriverInterface::WORK_PHONE => '"Номер рабочего телефона"',
            DriverInterface::BIRTH_DATE => '"Дата рождения"',

            // Driver License
            DriverLicenseInterface::ISSUE_COUNTRY => '"Страна выдачи ВУ"',
            DriverLicenseInterface::EXPIRATION_DATE => '"Дата окончания действия ВУ"',
            DriverLicenseInterface::ISSUE_DATE => '"Дата выдачи ВУ"',
            DriverLicenseInterface::NUMBER => '"Номер ВУ"',
            DriverLicenseInterface::ISSUE_DATE => '"Дата выдачи ВУ"',
            DriverLicenseInterface::EXPIRATION_DATE => '"Дата окончания действия ВУ"',

            // Car
            CarInterface::VIN => '"VIN-код"',
            CarInterface::BRAND => '"Марка автомобиля"',
            CarInterface::MODEL => '"Модель автомобиля"',
            CarInterface::ISSUE_YEAR => '"Год выпуска автомобиля"',
            CarInterface::COLOR => '"Цвет автомобиля"',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator(\Illuminate\Validation\Validator $validator)
    {
        $data = $validator->getData();
        $carBrand = $data['car_brand'];

        $knownCarBrands = $this->getKnownCarBrands();

        if (!in_array($carBrand, $knownCarBrands)) {
            return;
        }

        $knownCarModels = $this->getKnownCarBrandModels($carBrand);
        $rules = [
            'car_model' => [Rule::in($knownCarModels)]
        ];

        $validator->addRules($rules);
    }

    private function getKnownCarBrandModels(string $brand): array
    {
        return $this->carHelper->getKnownBrandModels($brand);
    }
}
