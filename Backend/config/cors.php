<?php

return [
    /*
     * Paths yang mengaktifkan CORS.
     * 'api/*' = semua endpoint API.
     */
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    /*
     * Ganti '*' dengan domain spesifik setelah deploy, contoh:
     *   ['https://jafdonation.id', 'https://www.jafdonation.id']
     */
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
