<?php

namespace Mylonia\SiteGuard;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Controller;

class SiteGuardController extends Controller
{
    public function __construct()
    {
        if (config('site-guard.password') == null) {
            throw new \Exception("`SITE_GUARD_PASSWORD` is not configured in your `.env` file.");
        }
    }

    private function verifyPassword(string $password): bool
    {
        $expectedPassword = config('site-guard.password');

        return $password == $expectedPassword;
    }

    private function alreadyHasSessionKey(): bool
    {
        return session()->get(config('site-guard.session_key')) === true;
    }

    private function setSessionKey(): void
    {
        session([config('site-guard.session_key') => true]);
    }

    private function getRedirectUrl(): string
    {
        $intendedUrl = session('intended_url', '/');

        session()->forget('intended_url');

        return $intendedUrl;
    }

    public function form(): View|RedirectResponse
    {
        if ($this->alreadyHasSessionKey()) {
            return redirect()->to('/');
        }

        return view('site-guard::form');
    }

    public function authenticate(Request $request): Redirector|RedirectResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if (!$this->verifyPassword($request->get('password'))) {
            return back()
                ->withErrors(['password' => 'Invalid password, please try again.'])
                ->withInput();
        }

        $this->setSessionKey();

        return redirect($this->getRedirectUrl());
    }
}