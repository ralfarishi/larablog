<?php

return [
  /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster used by the framework.
    | Laravel 13 uses BROADCAST_CONNECTION (not BROADCAST_DRIVER).
    |
    | Supported: "reverb", "pusher", "ably", "redis", "log", "null"
    |
    */

  'default' => env('BROADCAST_CONNECTION', env('BROADCAST_DRIVER', 'null')),

  /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    */

  'connections' => [
    'reverb' => [
      'driver' => 'reverb',
      'key' => env('REVERB_APP_KEY'),
      'secret' => env('REVERB_APP_SECRET'),
      'app_id' => env('REVERB_APP_ID'),
      'options' => [
        'host' => env('REVERB_HOST', '0.0.0.0'),
        'port' => env('REVERB_PORT', 8080),
        'scheme' => env('REVERB_SCHEME', 'http'),
        'useTLS' => env('REVERB_SCHEME', 'http') === 'https',
      ],
      'client_options' => [],
    ],

    'pusher' => [
      'driver' => 'pusher',
      'key' => env('PUSHER_APP_KEY'),
      'secret' => env('PUSHER_APP_SECRET'),
      'app_id' => env('PUSHER_APP_ID'),
      'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'host' => env('PUSHER_HOST') ?: 'api-' . env('PUSHER_APP_CLUSTER', 'mt1') . '.pusher.com',
        'port' => env('PUSHER_PORT', 443),
        'scheme' => env('PUSHER_SCHEME', 'https'),
        'encrypted' => true,
        'useTLS' => env('PUSHER_SCHEME', 'https') === 'https',
      ],
      'client_options' => [],
    ],

    'ably' => [
      'driver' => 'ably',
      'key' => env('ABLY_KEY'),
    ],

    'redis' => [
      'driver' => 'redis',
      'connection' => 'default',
    ],

    'log' => [
      'driver' => 'log',
    ],

    'null' => [
      'driver' => 'null',
    ],
  ],
];
