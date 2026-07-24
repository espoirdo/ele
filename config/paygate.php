<?php

return [
    'api_key'      => env('PAYGATE_API_KEY'),
    'base_url'     => env('PAYGATE_BASE_URL', 'https://paygateglobal.com'),
    'callback_url' => env('PAYGATE_CALLBACK_URL'),
];
