<?php

namespace Mylonia\SiteGuard\Tests;

use Mylonia\SiteGuard\SiteGuardServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            SiteGuardServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('site-guard.enabled', true);
        config()->set('site-guard.password', 'test-password');
        config()->set('site-guard.session_key', 'site_guard_authenticated');
        config()->set('site-guard.excluded_routes', []);
        config()->set('site-guard.excluded_paths', []);
    }
}