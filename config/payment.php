<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    */

    'currency' => 'PKR',

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [

        'easypaisa' => [
            'enabled' => env('EASYPAISA_ENABLED', false),

            'environment' => env(
                'EASYPAISA_ENVIRONMENT',
                'sandbox'
            ),

            'merchant_id' => env('EASYPAISA_MERCHANT_ID'),

            'store_id' => env('EASYPAISA_STORE_ID'),

            'username' => env('EASYPAISA_USERNAME'),

            'password' => env('EASYPAISA_PASSWORD'),

            'secret_key' => env('EASYPAISA_SECRET_KEY'),

            'base_url' => env(
                'EASYPAISA_BASE_URL'
            ),
        ],

        'jazzcash' => [
            'enabled' => env('JAZZCASH_ENABLED', false),

            'environment' => env(
                'JAZZCASH_ENVIRONMENT',
                'sandbox'
            ),

            'merchant_id' => env('JAZZCASH_MERCHANT_ID'),

            'password' => env('JAZZCASH_PASSWORD'),

            'integrity_salt' => env(
                'JAZZCASH_INTEGRITY_SALT'
            ),

            'base_url' => env(
                'JAZZCASH_BASE_URL'
            ),
        ],

        'bank_transfer' => [

            'enabled' => env(
                'BANK_TRANSFER_ENABLED',
                true
            ),

            'bank_name' => env(
                'BANK_TRANSFER_BANK_NAME'
            ),

            'account_title' => env(
                'BANK_TRANSFER_ACCOUNT_TITLE'
            ),

            'account_number' => env(
                'BANK_TRANSFER_ACCOUNT_NUMBER'
            ),

            'iban' => env(
                'BANK_TRANSFER_IBAN'
            ),

        ],

    ],

];
