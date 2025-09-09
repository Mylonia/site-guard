<?php

namespace Mylonia\SiteGuard\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Mylonia\SiteGuard\SiteGuardMiddleware;
use Mylonia\SiteGuard\Tests\TestCase;

class SiteGuardMiddlewareTest extends TestCase
{
    protected SiteGuardMiddleware $middleware;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new SiteGuardMiddleware();
    }

    private function assert(bool $intercepted, string $url = '/test'): void
    {
        $response = $this->middleware->handle(
            Request::create($url),
            fn () => new Response('success')
        );

        if ($intercepted) {
            $this->assertEquals(302, $response->getStatusCode());
            $this->assertTrue($response->isRedirect());
        } else {
            $this->assertEquals('success', $response->getContent());
        }
    }

    private function pretendCurrentRoute(string $name): void
    {
        Route::shouldReceive('currentRouteName')->andReturn($name);
    }

    public function test_allows_request_when_site_guard_disabled()
    {
        config(['site-guard.enabled' => true]);
        $this->assert(intercepted: true);

        config(['site-guard.enabled' => false]);
        $this->assert(intercepted: false);
    }

    public function test_allows_request_when_session_key_present()
    {
        $this->assert(intercepted: true);

        session(['site_guard_authenticated' => true]);
        $this->assert(intercepted: false);
    }

    public function test_allows_request_for_excluded_routes()
    {
        $this->pretendCurrentRoute('site-guard.form');
        $this->assert(intercepted: false, url: '/site-guard');
    }

    public function test_allows_request_for_user_excluded_routes()
    {
        config(['site-guard.excluded_routes' => ['admin.*']]);

        $this->pretendCurrentRoute('admin.dashboard');
        $this->assert(intercepted: false, url: '/admin/dashboard');
    }

    public function test_allows_request_for_excluded_paths()
    {
        config(['site-guard.excluded_paths' => ['api/*']]);

        $this->assert(intercepted: false, url: '/api/users');
    }

    public function test_stores_intended_url_in_session()
    {
        $request = Request::create('/dashboard?param=value');

        $this->middleware->handle($request, fn() => new Response());

        $this->assertStringContainsString('/dashboard?param=value', session('intended_url'));
    }

    public function test_route_exclusion_with_wildcard_patterns()
    {
        config(['site-guard.excluded_routes' => ['admin.*', 'api.v*']]);

        $this->pretendCurrentRoute('admin.users.create');
        $this->assert(intercepted: false, url: '/admin/users/create');

        $this->pretendCurrentRoute('api.v1.users');
        $this->assert(intercepted: false, url: '/api/v1/users');
    }

    public function test_path_exclusion_with_wildcard_patterns()
    {
        config(['site-guard.excluded_paths' => ['api/*', 'webhooks/*']]);

        $this->assert(intercepted: false, url: '/api/v1/users');
        $this->assert(intercepted: false, url: '/webhooks/stripe');
    }
}