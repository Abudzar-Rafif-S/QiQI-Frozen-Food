<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminShippingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ShippingRateController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;

use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController;

use App\Http\Controllers\Profile\AdminProfileController;
use App\Http\Controllers\Profile\CustomerProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ReviewController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Guest Routes (tanpa login)
|--------------------------------------------------------------------------
*/

// Root homepage tetap bisa diakses tanpa prefix
Route::get('/', [LandingController::class, 'index'])->name('guest.home');

Route::prefix('guest')->name('guest.')->group(function () {

    // Semua produk
    Route::get('/products', [LandingController::class, 'products'])->name('products');

    // Produk berdasarkan kategori
    Route::get(
        '/products/category/{categoryId}',
        [LandingController::class, 'productsByCategory']
    )->name('products.byCategory');

    // Detail produk
    Route::get(
        '/products/detail/{id}',
        [LandingController::class, 'productDetail']
    )->name('products.detail');


    // Search Menu
    Route::get('/search', [LandingController::class, 'search'])->name('search');

    // Halaman tentang kami / about us
    Route::get('/about', [LandingController::class, 'about'])->name('about');
});

/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {

    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'customer' => redirect()->route('customer.dashboard'),
        default => redirect()->route('guest.home'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::resource('/categories', CategoryController::class);
        Route::resource('/shipping-rates', ShippingRateController::class);
        Route::resource('/brands', BrandController::class);
        Route::resource('/products', ProductController::class);

        Route::patch('/products/{id}/stock', [ProductController::class, 'updateStock'])
            ->name('products.updateStock');

        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/shippings', [AdminShippingController::class, 'index'])->name('shippings.index');
        Route::post('/shippings/{id}/deliver', [AdminShippingController::class, 'deliver'])->name('shippings.deliver');
        Route::get('/shippings/{shipping}/invoice', [AdminShippingController::class, 'showInvoice'])
            ->name('shippings.invoice');
        Route::get('/orders/{orderId}', [AdminShippingController::class, 'showOrder'])->name('orders.show');
    });

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');

        // Semua Produk
        Route::get('/products', [CustomerController::class, 'products'])->name('products');

        // Produk berdasarkan kategori
        Route::get('/products/category/{categoryId}', [CustomerController::class, 'productsByCategory'])
            ->name('products.byCategory');

        // Detail produk
        Route::get('/products/detail/{id}', [CustomerController::class, 'productDetail'])
            ->name('products.detail');

        // Search Menu
        Route::get('/search', [CustomerController::class, 'search'])->name('search');

        // Profile: satu halaman dengan sidebar (index)
        Route::get('/profile', [CustomerProfileController::class, 'index'])
            ->name('profile.index');

        // Update profile — gunakan URI RESTful: PATCH /customer/profile
        Route::patch('/profile', [CustomerProfileController::class, 'update'])
            ->name('profile.update');

        // Delete account
        Route::delete('/profile', [CustomerProfileController::class, 'destroy'])
            ->name('profile.destroy');

        // Cart, Wishlist, dan Review
        Route::resource('/cart', CartController::class);
        Route::get('/cart/summary', [CartController::class, 'summary'])->name('cart.summary');
        Route::resource('/wishlist', WishlistController::class);
        Route::post('/products/{productId}/review', [ReviewController::class, 'store'])
            ->name('review.store');

        // 🔹 CHECKOUT FLOW
        Route::post('/cart/checkout', [CheckoutController::class, 'cartCheckout'])->name('cart.checkout');
        Route::get('/checkout/{invoice}', [CheckoutController::class, 'showOrder'])->name('checkout.show');
        Route::post('/checkout/estimate-shipping', [CheckoutController::class, 'estimateShipping'])->name('checkout.estimateShipping');
        Route::post('/checkout/update-customer', [CheckoutController::class, 'updateCustomerInfo'])->name('checkout.updateCustomer');
        Route::post('/checkout/process', [CheckoutController::class, 'checkout'])->name('checkout.process');
        // Di dalam grup customer
        Route::get('/orders/{order}/invoice', [OrderController::class, 'showInvoice'])
            ->name('orders.invoice');


        // ORDERS
        Route::prefix('orders')->name('orders.')->group(function () {
            // Daftar order customer
            Route::get('/', [OrderController::class, 'index'])->name('index');
            // Pay Again (AJAX/modal)
            Route::post('/pay-again/{order}', [OrderController::class, 'payAgain'])->name('pay-again');
            // Batalkan order
            Route::post('/cancel/{order}', [OrderController::class, 'cancel'])->name('cancel');
            Route::post('/{order}/accept-delivery', [OrderController::class, 'acceptDelivery'])
                ->name('accept-delivery');
        });
    });

/*
|--------------------------------------------------------------------------
| Midtrans Callback (Wajib Tanpa Middleware)
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/notification', [CheckoutController::class, 'midtransNotification'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('midtrans.notification');


/*
|--------------------------------------------------------------------------
| Default Profile (Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
