<?php

namespace Mylonia\SiteGuard;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class SiteGuardMiddleware
{
    /**
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next)
    {
        if (!config('site-guard.enabled')) {
            return $next($request);
        }

        if (session(config('site-guard.session_key'))) {
            return $next($request);
        }

        if ($this->isExcludedRoute(Route::currentRouteName())) {
            return $next($request);
        }

        if ($this->isExcludedPath($request->path())) {
            return $next($request);
        }

        if (config('site-guard.password') == null) {
            throw new \Exception("`SITE_GUARD_PASSWORD` is not configured in your `.env` file.");
        }

        return redirect()->route('site-guard.form')
            ->with('intended_url', $request->fullUrl());
    }

    private function isExcludedRoute(?string $routeName): bool
    {
        if (!$routeName) {
            return false;
        }

        $userProvidedRoutes = config('site-guard.excluded_routes', []);

        $excludedRoutes = array_merge($userProvidedRoutes, ['site-guard.*']);
        return array_any($excludedRoutes, fn($pattern) => fnmatch($pattern, $routeName));
    }

    private function isExcludedPath(string $path): bool
    {
        $excludedPaths = config('site-guard.excluded_paths', []);

        foreach ($excludedPaths as $pattern) {
            if (fnmatch($pattern, $path)) {
                return true;
            }
        }

        return false;
    }
}