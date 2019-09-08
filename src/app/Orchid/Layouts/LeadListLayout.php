<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\TD;
use Orchid\Screen\Layouts\Table;

class LeadListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $data = 'leads';

    /**
     * @return TD[]
     */
    public function fields(): array
    {
        return [
            TD::set('id', 'Id')->link('platform.lead.edit', 'id', 'id'),
            TD::set('phone_work','phone_work'),
//            TD::set('phone_whats_app','phone_whats_app'),
//            TD::set('city','city'),
//            TD::set('timezone','timezone'),
//            TD::set('country','country'),
            TD::set('first_name','first_name'),
            TD::set('last_name','last_name'),
            TD::set('middle_name','middle_name'),
            TD::set('driver_license_number','driver_license_number'),
//            TD::set('driver_license_issue_date','driver_license_issue_date'),
//            TD::set('driver_license_expiration_date','driver_license_expiration_date'),
//            TD::set('birth_date','birth_date'),
            TD::set('car_number','car_number'),
//            TD::set('car_brand','car_brand'),
//            TD::set('car_model','car_model'),
//            TD::set('car_year','car_year'),
//            TD::set('car_color','car_color'),
//            TD::set('car_vin','car_vin'),
            TD::set('registration_number','registration_number'),
//            TD::set('branding','branding'),

//            TD::set('created_at', 'Created'),
//            TD::set('updated_at', 'Last edit'),
        ];
    }
}
