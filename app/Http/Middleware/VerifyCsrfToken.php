<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'checkout/wayforpay/success',
        'wayforpay/success',
        'checkout/wayforpay/callback',
        'login/apple/callback',
    ];
}
