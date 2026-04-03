<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectMiddleware
{
    /**
     * Обрабатываем входящий запрос и, при необходимости, делаем 301‑редирект.
     *
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Только GET/HEAD, без AJAX/API/Nova
        if (! in_array($request->method(), ['GET', 'HEAD'], true)) {
            return $next($request);
        }

        $path = $request->getRequestUri(); // путь + query, напр. /old-path?utm=1
        $normalizedPath = Redirect::normalizePath($path);

        // Игнорируем Nova и служебные префиксы
        $novaPath = (string) config('nova.path', '/nova');
        if ($novaPath === '' || $novaPath[0] !== '/') {
            $novaPath = '/'.ltrim($novaPath, '/');
        }

        if (
            str_starts_with($normalizedPath, $novaPath) ||
            str_starts_with($normalizedPath, '/nova') ||
            str_starts_with($normalizedPath, '/nova-vendor') ||
            str_starts_with($normalizedPath, '/admin') ||
            str_starts_with($normalizedPath, '/api/')
        ) {
            return $next($request);
        }

        /** @var \App\Models\Redirect|null $redirect */
        $redirect = Redirect::query()
            ->where('is_active', true)
            ->where('from_url', $normalizedPath)
            ->first();

        if (! $redirect) {
            return $next($request);
        }

        $target = $redirect->to_url;

        // Если цель задана относительным путём — собираем абсолютный URL
        if (! str_starts_with($target, 'http://') && ! str_starts_with($target, 'https://')) {
            $target = url($target);
        }

        // На всякий случай защищаемся от очевидных саморедиректов
        if ($target === $request->fullUrl()) {
            return $next($request);
        }

        return redirect()->to($target, $redirect->status_code);
    }
}
