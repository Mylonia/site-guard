<?php

declare(strict_types=1);

return [
    'enabled' => env('SITE_GUARD_ENABLED', true),
    'password' => env('SITE_GUARD_PASSWORD'),
    'session_key' => 'site_guard_auth',
    'excluded_routes' => [],
    'middleware' => [
        Illuminate\Cookie\Middleware\EncryptCookies::class,
        Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        Illuminate\Session\Middleware\StartSession::class,
        Illuminate\View\Middleware\ShareErrorsFromSession::class,
        Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];
