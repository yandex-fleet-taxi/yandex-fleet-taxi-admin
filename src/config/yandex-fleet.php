<?php

return [
    'login' => env('YANDEX_FLEET_LOGIN'),
    'password' => env('YANDEX_FLEET_PASSWORD'),
    'park_id' => env('YANDEX_FLEET_PARK_ID'),

    'cors_host' => 'http://project1589609.tilda.ws',

    'default_post_data_values' => [
        'driver' => [
            'accounts' => [
                'balance_limit' => "5",
            ],

            'driver_profile' => [
                'providers' => [
                    'yandex'
                ],

                'work_rule_id' => 'a6cb3fbe61a54ba28f8f8b5e35b286db',

                'balance_deny_onlycard' => false,
            ],
        ],

        'car' => [
            'booster_count' => 0,
            'callsign' => 'тест',
            'cargo_loaders' => 0,
            'park_id' => '8d40b7c41af544afa0499b9d0bdf2430',
            'status' => 'working',//todo
            'transmission' => 'unknown',
        ]
    ],
];
