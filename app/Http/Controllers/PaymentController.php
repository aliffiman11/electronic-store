<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Order;
use App\Models\Product;
use App\Models\CustAddress;
use Illuminate\Http\Request;
use App\Events\NewOrderEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function redirectToStripe(Request $request)
    {
        // Initialize line items array
        $lineItems = [];

        if ($request->has('product_id')) {
            // Single product checkout
            $product = Product::findOrFail($request->input('product_id'));

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'myr',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => 1,
            ];
        } else {
            // Cart checkout
            $cart = session()->get('cart', []);

            foreach ($cart as $id => $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'myr',
                        'product_data' => [
                            'name' => $item['name'],
                        ],
                        'unit_amount' => $item['price'] * 100,
                    ],
                    'quantity' => $item['quantity'],
                ];
            }
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.form'),
                'shipping_address_collection' => [
                    'allowed_countries' => ['MY', 'US', 'SG'], // Add your allowed countries
                ],
            ]);

            return redirect($checkoutSession->url);
        } catch (\Exception $e) {
            return redirect()->route('checkout.form')->with('error', 'Error creating payment session: ' . $e->getMessage());
        }
    }

    public function handleSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            Log::error('Session ID is missing.');
            return redirect()->route('home')->with('error', 'Session ID is missing.');
        }

        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

            // Retrieve the Stripe session and line items
            $session = $stripe->checkout->sessions->retrieve($sessionId, []);
            $lineItems = $stripe->checkout->sessions->allLineItems($sessionId, []);
            $shippingDetails = $session->shipping_details;

            Log::info('Stripe Session: ', (array)$session);
            Log::info('Shipping Details: ', (array)$shippingDetails);

            $userId = Auth::id() ?? session()->get('user_id');
            if (!$userId) {
                Log::error('User not authenticated.');
                return redirect()->route('home')->with('error', 'User not authenticated.');
            }

            $orderReference = $session->id;
            $totalPrice = 0;

            // Save the shipping address
            if ($shippingDetails && isset($shippingDetails->address)) {
                CustAddress::create([
                    'user_id' => $userId,
                    'order_reference' => $orderReference,
                    'address_line' => $shippingDetails->address->line1 ?? 'N/A',
                    'city' => $shippingDetails->address->city ?? 'N/A',
                    'state' => $shippingDetails->address->state ?? 'N/A',
                    'postal_code' => $shippingDetails->address->postal_code ?? 'N/A',
                ]);
            } else {
                Log::warning('No shipping address found in the Stripe session.');
            }

            // Save each purchased item as an order
            foreach ($lineItems->data as $item) {
                $product = Product::where('name', $item->description)->first();

                $order = Order::create([
                    'order_reference' => $orderReference,
                    'user_id' => $userId,
                    'product_id' => $product ? $product->id : null,
                    'quantity' => $item->quantity,
                    'price' => $item->price->unit_amount / 100,
                    'total_price' => ($item->price->unit_amount / 100) * $item->quantity,
                    'status' => 'paid',
                ]);

                $totalPrice += $order->total_price;
            }

            event(new NewOrderEvent($order));
            Log::info('NewOrderEvent fired for order:', $order->toArray());


            session()->forget('cart');

            return redirect()->route('checkout.success')->with('success', 'Payment successful! Your order has been placed.');
        } catch (\Exception $e) {
            Log::error('Error in handleSuccess: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'An error occurred while processing your payment.');
        }
    }
}
