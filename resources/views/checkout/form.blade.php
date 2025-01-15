@extends('layouts.master')

@section('title', 'Checkout')

<style>
    .card:hover {
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
}

.btn-primary:hover {
    background-color: #004085;
    color: white;
}


</style>

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h2 class="mb-0">Checkout</h2>
                </div>
                <div class="card-body">
                    <!-- Invoice Summary -->
                    <div class="cart-summary mb-4">
                        <h4 class="text-secondary">Your Invoice</h4>
                        <ul class="list-group">
                            @if(isset($product))
                                <!-- Direct Buy Invoice -->
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">{{ $product->name }}</span>
                                    <span class="text-success">RM {{ number_format($product->price, 2) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                    <strong>Total</strong>
                                    <strong class="text-success">RM {{ number_format($product->price, 2) }}</strong>
                                </li>
                            @elseif(isset($cart) && count($cart) > 0)
                                <!-- Cart Checkout Invoice -->
                                @foreach($cart as $id => $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                                        <span class="text-success">RM {{ number_format($item['price'], 2) }}</span>
                                    </li>
                                @endforeach
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                    <strong>Total</strong>
                                    <strong class="text-success">RM {{ number_format($totalPrice, 2) }}</strong>
                                </li>
                            @else
                                <li class="list-group-item text-center">No items to checkout.</li>
                            @endif
                        </ul>
                    </div>

                    <!-- Payment Method Form -->
                    <div class="payment-method">
                        <h4 class="text-secondary mb-3">Confirm and Proceed to Payment</h4>
                        <form action="{{ route('checkout.pay') }}" method="POST">
                            @csrf
                            @if(isset($product))
                                <!-- Direct Buy -->
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                            @endif
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Proceed to Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
