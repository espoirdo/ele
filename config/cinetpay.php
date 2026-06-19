<?php

return [
    'api_key' => env('CINETPAY_API_KEY', ''),
    'site_id' => env('CINETPAY_SITE_ID', ''),
    'sandbox' => env('CINETPAY_SANDBOX', true),
    'endpoint' => env('CINETPAY_ENDPOINT', 'https://api-checkout.cinetpay.com/v2/?method=CHECKOUT'),
];
