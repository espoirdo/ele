<?php

return [
    'api_key'    => env('PZGATE_API_KEY'),
    'secret_key' => env('PZGATE_SECRET_KEY'),
    'base_url'   => env('PZGATE_BASE_URL', 'https://api.pzgate.com/v1'),
    'currency'   => env('PZGATE_CURRENCY', 'XOF'),
    'callback_url' => env('APP_URL') . '/webhook/pzgate',
    'return_url'   => env('APP_URL') . '/paiement/confirmation',
];
