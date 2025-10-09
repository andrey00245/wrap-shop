<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CacheHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Кэширование статических ресурсов
        if ($request->is('build/*') || $request->is('assets/*')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        }
        
        // Кэширование изображений
        if ($request->is('storage/*') || $request->is('media-library/*')) {
            $response->headers->set('Cache-Control', 'public, max-age=2592000'); // 30 дней
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 2592000) . ' GMT');
        }

        // Кэширование HTML страниц
        if ($request->isMethod('GET') && !$request->is('admin/*') && !$request->is('nova/*')) {
            $response->headers->set('Cache-Control', 'public, max-age=3600'); // 1 час
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
        }

        return $response;
    }
}
