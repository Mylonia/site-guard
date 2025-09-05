<?php declare(strict_types=1);

namespace Mylonia\SiteGuard;

use Illuminate\Support\ServiceProvider;

class SiteGuardServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/site-guard.php' => config_path('site-guard.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/site-guard'),
        ], 'views');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'site-guard');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/site-guard.php', 'site-guard');
    }
}