<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Likemusic\YandexFleetTaxi\FrontendData\Contracts\DriverInterface;
use Likemusic\YandexFleetTaxi\FrontendData\Contracts\DriverLicenseInterface;
use Likemusic\YandexFleetTaxi\FrontendData\Contracts\CarInterface;

class Lead extends FormRequest
{
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

            // Driver License
            DriverLicenseInterface::ISSUE_COUNTRY => $required,
            DriverLicenseInterface::EXPIRATION_DATE => $required,
            DriverLicenseInterface::ISSUE_DATE => $required,
            DriverLicenseInterface::SERIES => $required,
            DriverLicenseInterface::NUMBER => $required,

            // Car
            CarInterface::BRAND => $required,
            CarInterface::COLOR => $required,
            CarInterface::MODEL => $required,
            CarInterface::NUMBER => $required,
            CarInterface::REGISTRATION => $required,
            CarInterface::VIN => "required|size:17",
            CarInterface::ISSUE_YEAR => $required,
        ];
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

            // Driver License
            DriverLicenseInterface::ISSUE_COUNTRY => '"Страна выдачи ВУ"',
            DriverLicenseInterface::EXPIRATION_DATE => '"Дата окончания действия ВУ"',
            DriverLicenseInterface::ISSUE_DATE => '"Дата выдачи ВУ"',
            DriverLicenseInterface::SERIES => '"Серия ВУ"',
            DriverLicenseInterface::NUMBER => '"Номер ВУ"',

            // Car
            CarInterface::VIN => '"VIN-код"'
        ];
    }
}
