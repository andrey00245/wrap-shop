<?php

use App\Http\Middleware\RedirectCanonicalWithoutIndexPhp;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Http\Middleware\RedirectMiddleware;
use App\Http\Middleware\RedirectStripTrailingSlash;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Редиректи з БД — у глобальному стеку: інакше при «немає маршруту» (404) група web не виконується
        // і RedirectMiddleware взагалі не викликається (наприклад, коли ->where('path', ...) не збігається з URI).
        $middleware->prepend(RedirectMiddleware::class);

        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\CartMiddleware::class,
        ]);
        $middleware->web(prepend: [
            RedirectCanonicalWithoutIndexPhp::class,
            RedirectStripTrailingSlash::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\CartMiddleware::class,
            //            \App\Http\Middleware\RedirectIfNotAuthenticated::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        $middleware->alias([
            /**** OTHER MIDDLEWARE ALIASES ****/
            'localize' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localeCookieRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
            'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
            'themeMiddleware' => \App\Http\Middleware\ThemeMiddleware::class,
            'redirect_if_not_authenticated' => RedirectIfNotAuthenticated::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Подавляем ошибку "Broken pipe" при записи файлов
        $exceptions->report(function (\Throwable $e) {
            $message = $e->getMessage();
            $file = $e->getFile();

            // Подавляем ошибки "Broken pipe" при записи файлов (возникают при разрыве соединения)
            if (str_contains($message, 'file_put_contents') &&
                (str_contains($message, 'Broken pipe') ||
                 str_contains($message, 'errno=32'))) {
                return false; // Не логируем эту ошибку
            }

            // Подавляем Notice для Broken pipe
            if (str_contains($message, 'Notice') &&
                (str_contains($message, 'Broken pipe') ||
                 str_contains($message, 'errno=32'))) {
                return false;
            }

            // Подавляем ошибки из server.php (Laravel development server)
            if (str_contains($file, 'server.php') &&
                (str_contains($message, 'Broken pipe') ||
                 str_contains($message, 'errno=32'))) {
                return false;
            }
        });

        // Скрываем ошибки Broken pipe из вывода (включая админку)
        $exceptions->render(function (\Throwable $e, $request) {
            $message = $e->getMessage();
            $file = $e->getFile();

            if ((str_contains($message, 'file_put_contents') || str_contains($file, 'server.php')) &&
                (str_contains($message, 'Broken pipe') ||
                 str_contains($message, 'errno=32') ||
                 str_contains($message, 'Notice'))) {
                // Для админки возвращаем пустой ответ без ошибки
                if ($request->is('nova*') || $request->is('admin*')) {
                    return response('', 200);
                }

                // Для обычных страниц тоже скрываем
                return response('', 200);
            }
        });
    })->create();

// Подавляем PHP Notice/Warning для Broken pipe через error handler
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    // Подавляем ошибки Broken pipe
    if (str_contains($errstr, 'file_put_contents') &&
        (str_contains($errstr, 'Broken pipe') ||
         str_contains($errstr, 'errno=32'))) {
        return true; // Подавляем ошибку
    }

    // Подавляем ошибки из server.php
    if (str_contains($errfile, 'server.php') &&
        (str_contains($errstr, 'Broken pipe') ||
         str_contains($errstr, 'errno=32'))) {
        return true; // Подавляем ошибку
    }

    // Возвращаем false для других ошибок, чтобы они обрабатывались стандартным образом
    return false;
}, E_WARNING | E_NOTICE);
