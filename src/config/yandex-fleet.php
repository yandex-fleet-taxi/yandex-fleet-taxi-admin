<?php

return [
    'login' => env('YANDEX_FLEET_LOGIN'),
    'password' => env('YANDEX_FLEET_PASSWORD'),
    'park_id' => env('YANDEX_FLEET_PARK_ID'),

    'cors_host' => env('CORS_HOST'),

    'default_post_data_values' => [
        'driver' => [
            'accounts' => [
                'balance_limit' => "5",
            ],

            'driver_profile' => [
                'providers' => [
                    'yandex'
                ],

                'balance_deny_onlycard' => false,
                'work_rule_id' => env('YANDEX_FLEET_WORK_RULE_ID'),
            ],
        ],

        'car' => [
            'status' => 'working',//todo
            'callsign' => 'тест',
            'booster_count' => 0,
            'categories' => [],
            'carrier_permit_owner_id' => null,
            'transmission' => 'unknown',
            'rental' => null,
            'chairs' => [],
            'tariffs' => [],
            'body_number' => null,
            'service_check_expiration_date' => null,
            'car_insurance_expiration_date' => null,
            'car_authorization_expiration_date' => null,
            'insurance_for_goods_and_passengers_expiration_date' => null,
            'badge_for_alternative_transport_expiration_date' => null,
            'amenities' => [],
            'permit_num' => null,
        ]
    ],

    'curl_options' => [
//        CURLOPT_PROXY => 'host.docker.internal:8888',
        CURLOPT_PROXY => null,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
    ]
];
