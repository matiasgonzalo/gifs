<?php

return [
    'api_key' => env('GIF_API_KEY', ''),
    'base_uri' => env('GIF_BASE_URI', ''),
    'timeout_seconds' => 60,
    'search' => [
        'limit_default' => 25,
        'offset_default' => 0,
    ],
];
