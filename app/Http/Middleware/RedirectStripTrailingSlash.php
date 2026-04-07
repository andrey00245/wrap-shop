<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Канонічний URL без завершального слеша (крім кореня) — 301.
 * На Apache часто вже робить public/.htaccess; для Nginx / artisan serve — тут.
 */
class RedirectStripTrailingSlash
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! in_array($request->method(), ['GET', 'HEAD'], true)) {
            return $next($request);
        }

        $path = $request->getPathInfo();

        $novaPath = (string) config('nova.path', '/nova');
        if ($novaPath === '' || $novaPath[0] !== '/') {
            $novaPath = '/'.ltrim($novaPath, '/');
        }

        if (
            str_starts_with($path, $novaPath)
            || str_starts_with($path, '/nova')
            || str_starts_with($path, '/nova-vendor')
            || str_starts_with($path, '/admin')
            || str_starts_with($path, '/api/')
        ) {
            return $next($request);
        }

        if ($path === '/' || $path === '' || ! str_ends_with($path, '/')) {
            return $next($request);
        }

        $canonicalPath = rtrim($path, '/') ?: '/';
        $target = $request->getSchemeAndHttpHost().$canonicalPath;
        $qs = $request->getQueryString();
        if ($qs !== null && $qs !== '') {
            $target .= '?'.$qs;
        }

        if ($target === $request->fullUrl()) {
            return $next($request);
        }

        return redirect()->to($target, 301);
    }
}
