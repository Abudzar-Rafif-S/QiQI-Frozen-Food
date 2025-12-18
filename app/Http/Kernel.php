<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Route middleware untuk project
     */
    protected $routeMiddleware = [
        'auth'  => \App\Http\Middleware\Auth\Authenticate::class,
        'guest' => \App\Http\Middleware\Auth\RedirectIfAuthenticated::class,
        'role'  => \App\Http\Middleware\RoleMiddleware::class,
        'csrf'  => \App\Http\Middleware\VerifyCsrfTokenCustom::class,
    ];
}
