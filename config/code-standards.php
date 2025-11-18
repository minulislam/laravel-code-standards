<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Code Standards Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your code review and linting standards here.
    |
    */

    'phpstan' => [
        'enabled' => true,
        'level' => 8,
        'paths' => ['app', 'config', 'database', 'routes'],
        'memory_limit' => '2G',
    ],

    'pint' => [
        'enabled' => true,
        'preset' => 'laravel',
    ],

    'phpcs' => [
        'enabled' => true,
        'standard' => 'PSR12',
    ],

    /*
    |--------------------------------------------------------------------------
    | Review Rules
    |--------------------------------------------------------------------------
    |
    | Enable/disable specific review checks
    |
    */

    'rules' => [
        'strict_types' => true,
        'type_hints' => true,
        'visibility_modifiers' => true,
        'no_static_business_logic' => true,
        'dependency_injection' => true,
        'laravel_facades_allowed' => true,
        'form_requests' => true,
        'api_resources' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Laravel Facades
    |--------------------------------------------------------------------------
    |
    | List of Laravel facades that are explicitly allowed
    |
    */

    'allowed_facades' => [
        'Artisan', 'Auth', 'Blade', 'Broadcast', 'Bus', 'Cache', 'Config',
        'Cookie', 'Crypt', 'Date', 'DB', 'Eloquent', 'Event', 'File',
        'Gate', 'Hash', 'Http', 'Lang', 'Log', 'Mail', 'Notification',
        'Password', 'Queue', 'RateLimiter', 'Redirect', 'Redis', 'Request',
        'Response', 'Route', 'Schema', 'Session', 'Storage', 'URL', 'Validator', 'View',
    ],

    /*
    |--------------------------------------------------------------------------
    | Exclusions
    |--------------------------------------------------------------------------
    |
    | Paths to exclude from code review
    |
    */

    'exclude' => [
        'vendor',
        'node_modules',
        'storage',
        'bootstrap/cache',
        'public',
    ],
];
