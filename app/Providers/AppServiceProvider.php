<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
        View()->composer('*', function ($view) {
            if (Auth::check()) {
                $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
            } else {
                $cartItems = collect(); // Empty collection for guests
            }
            $view->with('cartItems', $cartItems);
        });
    }
}
