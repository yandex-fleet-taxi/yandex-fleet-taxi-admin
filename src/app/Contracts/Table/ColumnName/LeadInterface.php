<?php

namespace App\Contracts\Table\ColumnName;

interface LeadInterface
{
    const ID = 'id';
    const PHONE_WORK = 'phone_work';
    const PHONE_WHATS_APP = 'phone_whats_app';
    const CITY = 'city';
    const TIMEZONE = 'timezone';
    const COUNTRY = 'country';

    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const MIDDLE_NAME = 'middle_name';


    const DRIVER_LICENSE_NUMBER = 'driver_license_number';
    const DRIVER_LICENSE_ISSUE_DATE = 'driver_license_issue_date';
    const DRIVER_LICENSE_EXPIRATION_DATE = 'driver_license_expiration_date';
    const BIRTH_DATE = 'birth_date';

    const CAR_NUMBER = 'car_number';
    const CAR_BRAND = 'car_brand';
    const CAR_MODEL = 'car_model';
    const CAR_YEAR = 'car_year';
    const CAR_COLOR = 'car_color';
    const CAR_VIN = 'car_vin';

    const REGISTRATION_NUMBER = 'registration_number';
    const BRANDING = 'branding';
}
