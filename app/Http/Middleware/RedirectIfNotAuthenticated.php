<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Safety guard: this middleware is intended only for /account pages.
        // If it is accidentally applied globally, do not break public pages.
        if (! str_starts_with('/'.ltrim($request->path(), '/'), '/account')) {
            return $next($request);
        }

        if (! Auth::check() && $request->path() !== '/') {
            return redirect('/');
        }

        return $next($request);
    }
}
