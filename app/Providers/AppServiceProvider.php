<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // GLOBAL NAVBAR DATA
        View::composer('*', function ($view) {
            $cartCount = 0;
            $navbarCustomer = null;

            if (Auth::check()) {
                if (Auth::user()->isCustomer() && Auth::user()->customer) {
                    $navbarCustomer = Auth::user()->customer;
                    $cartCount = Cart::where('customer_id', $navbarCustomer->id)->count();
                }
            }

            $view->with('cartCount', $cartCount);
            $view->with('navbarCustomer', $navbarCustomer);
        });

        /**
         * MIDTRANS CONFIG
         * Penting: memastikan server key & client key tidak null
         */
        \Midtrans\Config::$serverKey     = config('midtrans.server_key');
        \Midtrans\Config::$clientKey     = config('midtrans.client_key');
        \Midtrans\Config::$isProduction  = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized   = true;
        \Midtrans\Config::$is3ds         = true;

    }
}
