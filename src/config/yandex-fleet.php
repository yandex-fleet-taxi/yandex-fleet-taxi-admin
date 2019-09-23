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
            'booster_count' => 0,
            'callsign' => 'тест',
            'cargo_loaders' => 0,
            'park_id' => env('YANDEX_FLEET_PARK_ID'),
            'status' => 'working',//todo
            'transmission' => 'unknown',
        ]
    ],

    'curl_options' => [
        CURLOPT_PROXY => 'host.docker.internal222:8888',
//        CURLOPT_SSL_VERIFYHOST => 0,
//        CURLOPT_SSL_VERIFYPEER => 0,
    ]
];
