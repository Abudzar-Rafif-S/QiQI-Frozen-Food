<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cek apakah role user sesuai
        if (Auth::user()->role !== $role) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}
