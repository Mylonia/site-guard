<?php

use Illuminate\Support\Facades\Route;
use Mylonia\SiteGuard\SiteGuardController;

$middleware = [
    Illuminate\Cookie\Middleware\EncryptCookies::class,
    Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    Illuminate\Session\Middleware\StartSession::class,
    Illuminate\View\Middleware\ShareErrorsFromSession::class,
    Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    Illuminate\Routing\Middleware\SubstituteBindings::class,
];

Route::middleware($middleware)->group(function () {
    Route::get('/site-guard', [SiteGuardController::class, 'form'])
        ->name('site-guard.form');

    Route::post('/site-guard', [SiteGuardController::class, 'authenticate'])
        ->name('site-guard.authenticate');
});

