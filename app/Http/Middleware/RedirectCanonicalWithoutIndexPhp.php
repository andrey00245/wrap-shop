<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 301 на канонічний URL без index.php у шляху (дублікати в пошуку).
 */
class RedirectCanonicalWithoutIndexPhp
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! in_array($request->method(), ['GET', 'HEAD'], true)) {
            return $next($request);
        }

        $path = $request->getPathInfo();
        if ($path === '' || stripos($path, 'index.php') === false) {
            return $next($request);
        }

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

        $canonical = $this->canonicalPath($path);
        if ($canonical === null || $canonical === $path) {
            return $next($request);
        }

        $target = $canonical;
        $qs = $request->getQueryString();
        if ($qs !== null && $qs !== '') {
            $target .= '?'.$qs;
        }

        $url = $request->getSchemeAndHttpHost().$target;

        if ($url === $request->fullUrl()) {
            return $next($request);
        }

        return redirect()->to($url, 301);
    }

    private function canonicalPath(string $path): ?string
    {
        if (preg_match('#^/index\.php$#i', $path)) {
            return '/';
        }

        if (preg_match('#^/index\.php/(.+)$#i', $path, $m)) {
            return '/'.ltrim($m[1], '/');
        }

        if (preg_match('#^(.+)/index\.php/?$#i', $path, $m)) {
            $base = $m[1];

            return $base === '' ? '/' : $base;
        }

        return null;
    }
}
