@extends('layouts.master')

@section('title', 'Payment Canceled')

@section('content')
    <h1>Payment Canceled</h1>
    <p>Your payment was not completed. Please try again.</p>
    <a href="{{ route('checkout.form') }}" class="btn btn-secondary">Return to Checkout</a>
@endsection
