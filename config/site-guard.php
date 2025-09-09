<?php

declare(strict_types=1);

return [
    'enabled' => env('SITE_GUARD_ENABLED', true),
    'password' => env('SITE_GUARD_PASSWORD'),
    'session_key' => 'site_guard_auth',
    'excluded_routes' => [],
];
