<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use App\Contracts\Table\ColumnName\LeadInterface;

class Lead extends Model
{
    use AsSource;

    protected $fillable = [
        LeadInterface::PHONE_WORK,
        LeadInterface::PHONE_WHATS_APP,
        LeadInterface::CITY,
        LeadInterface::TIMEZONE,
        LeadInterface::COUNTRY,
        LeadInterface::FIRST_NAME,
        LeadInterface::LAST_NAME,
        LeadInterface::MIDDLE_NAME,
        LeadInterface::DRIVER_LICENSE_NUMBER,
        LeadInterface::DRIVER_LICENSE_ISSUE_DATE,
        LeadInterface::DRIVER_LICENSE_EXPIRATION_DATE,
        LeadInterface::BIRTH_DATE,
        LeadInterface::CAR_NUMBER,
        LeadInterface::CAR_BRAND,
        LeadInterface::CAR_MODEL,
        LeadInterface::CAR_YEAR,
        LeadInterface::CAR_COLOR,
        LeadInterface::CAR_VIN,
        LeadInterface::REGISTRATION_NUMBER,
        LeadInterface::BRANDING,
    ];
}
