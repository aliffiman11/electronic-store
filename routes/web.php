<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home'); // Public homepage
Route::get('/products', [HomeController::class, 'products'])->name('products'); // Public products page

Route::middleware(['auth', 'role:customer'])->group(function () {

    //handle cart process
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart/items', [CartController::class, 'viewCart'])->name('cart.items');
    Route::post('/cart/update/{id}', [CartController::class, 'updateCartItem'])->name('cart.update');
    Route::post('/cart/delete/{id}', [CartController::class, 'removeCartItem'])->name('cart.remove');
    Route::get('/cart/view', [CartController::class, 'viewCart'])->name('cart.view');

    //handle checkout process
    Route::get('/checkout', [CartController::class, 'showCheckoutForm'])->name('checkout.form');

    //handle payment process
    Route::post('/checkout/pay', [PaymentController::class, 'redirectToStripe'])->name('checkout.pay');

    // Success route for Stripe payment
    Route::get('/payment/success', [PaymentController::class, 'handleSuccess'])->name('payment.success');

    Route::get('/checkout/success', function () {
        return view('checkout.success');
    })->name('checkout.success');

    // Cancel route for Stripe payment (optional, but good to have)
    Route::get('/payment/cancel', function () {
        return view('checkout.cancel'); // Create a view for cancellation if needed
    })->name('checkout.cancel');

    Route::get('/cart/clear', function () {
        session()->forget('cart');
        return 'Cart cleared!';
    });

    //customer invoice
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/invoice/download/{orderReference}', [CustomerController::class, 'downloadInvoice'])->name('invoice.download');

});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

});

/*
|--------------------------------------------------------------------------
| Profile and Authentication
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
