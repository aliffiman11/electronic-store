<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Charge;

class CartController extends Controller
{

    public function fetchCartItems()
    {
        $cart = session()->get('cart', []);
        $html = view('partials.cart-items', compact('cart'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    // Add to Cart
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += 1;
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        $totalPrice = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $html = view('partials.cart-items', ['cart' => $cart])->render();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'html' => $html,
            'totalPrice' => $totalPrice,
        ]);
    }

    // View Cart
    public function viewCart()
    {
        $cart = session()->get('cart', []);

        $html = view('partials.cart-items', compact('cart'))->render();

        return response()->json(['html' => $html]);
    }

    // Update Cart Item
    public function updateCartItem(Request $request, $id)
    {
        $action = $request->input('action');
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($action === 'increase') {
                $cart[$id]['quantity'] += 1;
            } elseif ($action === 'decrease' && $cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity'] -= 1;
            }
        }

        session()->put('cart', $cart);

        $totalPrice = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $html = view('partials.cart-items', ['cart' => $cart])->render();

        return response()->json(['success' => true, 'html' => $html, 'totalPrice' => $totalPrice]);
    }

    // Remove Cart Item
    public function removeCartItem($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        session()->put('cart', $cart);

        $totalPrice = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        $html = view('partials.cart-items', ['cart' => $cart])->render();

        return response()->json(['success' => true, 'html' => $html, 'totalPrice' => $totalPrice]);
    }

    // redirect to checkout form
    public function showCheckoutForm(Request $request)
    {
        $cart = session()->get('cart', []);
        $totalPrice = 0;

        if ($request->has('product_id')) {
            // Direct Buy: Set single product session
            $product = Product::findOrFail($request->input('product_id'));
            session()->put('single_product', $product); // Set session for direct buy

            return view('checkout.form', [
                'product' => $product,
                'cart' => null,
                'totalPrice' => $product->price,
            ]);
        } elseif (!empty($cart)) {
            // Cart Checkout: Calculate total price for cart items
            $totalPrice = array_sum(array_map(function ($item) {
                return $item['price'] * $item['quantity'];
            }, $cart));

            return view('checkout.form', compact('cart', 'totalPrice'));
        }

        return redirect()->route('home')->with('error', 'No items to checkout.');
    }
}
