<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Likemusic\YandexFleetTaxi\FrontendData\Contracts\DriverInterface;

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
     *
     * @return array
     */
    public function rules()
    {
        return [
            DriverInterface::FIRST_NAME => 'required',
            DriverInterface::LAST_NAME => 'required',
            DriverInterface::MIDDLE_NAME => 'required',
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
            DriverInterface::FIRST_NAME => 'Имя',
            DriverInterface::LAST_NAME => 'Фамилия',
            DriverInterface::MIDDLE_NAME => 'Отчество',
        ];
    }
}
