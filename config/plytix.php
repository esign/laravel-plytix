<?php

return [
    /**
     * The API key to be used for authenticating with the Plytix API.
     */
    'api_key' => env('PLYTIX_API_KEY'),

    /**
     * The API password to be used for authenticating with the Plytix API.
     */
    'api_password' => env('PLYTIX_API_PASSWORD'),

    'authenticator_cache' => [
        /**
         * The key that will be used to cache the Plytix access token.
         */
        'key' => 'esign.plytix.authenticator',

        /**
         * The cache store to be used for the Plytix access token.
         * Use null to utilize the default cache store from the cache.php config file.
         * To disable caching, you can use the 'array' store.
         */
        'store' => null,
    ],

    'rate_limiting' => [
        /**
         * The cache store to be used for the Plytix rate limits.
         * Use null to utilize the default cache store from the cache.php config file.
         * To disable caching, you can use the 'array' store.
         */
        'cache_store' => null,
    ],
];
