<?php

namespace Mylonia\SiteGuard;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class SiteGuardMiddleware
{
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

        foreach ($excludedRoutes as $pattern) {
            if (fnmatch($pattern, $routeName)) {
                return true;
            }
        }

        return false;
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