<?php

declare(strict_types=1);

namespace Mylonia\SiteGuard\Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Mylonia\SiteGuard\Tests\TestCase;

class SiteGuardControllerTest extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_form_displays_when_not_authenticated(): void
    {
        $this->get('/site-guard')
            ->assertStatus(200)
            ->assertViewIs('site-guard::form');
    }

    public function test_form_redirects_when_already_authenticated(): void
    {
        session(['site_guard_authenticated' => true]);

        $this->get('/site-guard')
            ->assertStatus(302)
            ->assertRedirect('/');
    }

    public function test_authenticate_with_valid_password(): void
    {
        $this->post('/site-guard', ['password' => 'test-password'])
            ->assertStatus(302);

        $this->assertTrue(session('site_guard_authenticated'));
    }

    public function test_authenticate_with_invalid_password(): void
    {
        $this->post('/site-guard', ['password' => 'wrong-password'])
            ->assertStatus(302)
            ->assertSessionHasErrors(['password']);

        $this->assertNull(session('site_guard_authenticated'));
    }

    public function test_authenticate_requires_password(): void
    {
        $this->post('/site-guard', [])
            ->assertStatus(302)
            ->assertSessionHasErrors(['password']);
    }

    public function test_redirects_to_intended_url_after_authentication(): void
    {
        session(['intended_url' => '/dashboard']);

        $this->post('/site-guard', ['password' => 'test-password'])
            ->assertStatus(302)
            ->assertRedirect('/dashboard');

        $this->assertNull(session('intended_url'));
    }

    public function test_redirects_to_home_when_no_intended_url(): void
    {
        $this->post('/site-guard', ['password' => 'test-password'])
            ->assertStatus(302)
            ->assertRedirect('/');
    }

    public function test_form_retains_input_on_validation_error(): void
    {
        $this->post('/site-guard', ['password' => 'wrong-password'])
            ->assertStatus(302)
            ->assertSessionHasInput('password');
    }

    public function test_intended_url_is_removed_from_session_after_redirect(): void
    {
        session(['intended_url' => '/admin']);

        $this->post('/site-guard', ['password' => 'test-password'])
            ->assertRedirect('/admin');

        $this->assertNull(session('intended_url'));
    }
}
