<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Mylonia\SiteGuard\SiteGuardController;

Route::middleware(config('site-guard.middleware'))->group(function () {
    Route::get('/site-guard', [SiteGuardController::class, 'form'])
        ->name('site-guard.form');

    Route::post('/site-guard', [SiteGuardController::class, 'authenticate'])
        ->name('site-guard.authenticate');
});
