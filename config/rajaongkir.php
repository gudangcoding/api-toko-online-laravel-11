<?php

return [
    // API key dan base URL
    'api_key' => env('RAJAONGKIR_API_KEY', env('RAJAONGKIR_KEY')),
    'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),

    // Paket langganan: starter, basic, pro
    'package' => env('RAJAONGKIR_PACKAGE', 'starter'),

    // Daftar kurir per paket langganan
    'couriers' => [
        'starter' => [
            ['code' => 'jne', 'name' => 'JNE'],
            ['code' => 'pos', 'name' => 'POS Indonesia'],
            ['code' => 'tiki', 'name' => 'TIKI'],
        ],
        'basic' => [
            ['code' => 'jne', 'name' => 'JNE'],
            ['code' => 'pos', 'name' => 'POS Indonesia'],
            ['code' => 'tiki', 'name' => 'TIKI'],
            ['code' => 'rpx', 'name' => 'RPX'],
            ['code' => 'pcp', 'name' => 'PCP Express'],
            ['code' => 'esl', 'name' => 'ESL Express'],
        ],
        'pro' => [
            ['code' => 'jne', 'name' => 'JNE'],
            ['code' => 'pos', 'name' => 'POS Indonesia'],
            ['code' => 'tiki', 'name' => 'TIKI'],
            ['code' => 'rpx', 'name' => 'RPX'],
            ['code' => 'pcp', 'name' => 'PCP Express'],
            ['code' => 'esl', 'name' => 'ESL Express'],
            ['code' => 'ncs', 'name' => 'NCS Express'],
            ['code' => 'sicepat', 'name' => 'SiCepat'],
            ['code' => 'jet', 'name' => 'JET Express'],
            ['code' => 'sap', 'name' => 'SAP Express'],
            ['code' => 'first', 'name' => 'First Logistics'],
            ['code' => 'ninja', 'name' => 'Ninja Express'],
            ['code' => 'lion', 'name' => 'Lion Parcel'],
            ['code' => 'idl', 'name' => 'IDL Cargo'],
            ['code' => 'rex', 'name' => 'Royal Express'],
            ['code' => 'ide', 'name' => 'ID Express'],
            ['code' => 'sentral', 'name' => 'Sentral Cargo'],
        ],
    ],
];