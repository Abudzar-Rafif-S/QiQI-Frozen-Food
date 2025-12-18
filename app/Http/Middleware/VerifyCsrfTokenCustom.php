<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfTokenCustom extends Middleware
{
    /**
     * URIs yang dikecualikan dari CSRF verification
     *
     * @var array<int, string>
     */
    protected $except = [
        'midtrans/callback', // tambahkan route callback Midtrans
    ];
}
