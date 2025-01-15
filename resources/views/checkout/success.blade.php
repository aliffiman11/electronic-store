@extends('layouts.master')

@section('title', 'Payment Successful')

@section('content')
<div class="container mt-5">
    <div class="alert alert-success text-center">
        <h1>Thank You for Your Purchase!</h1>
        <p>Your payment was successful. Your order has been placed.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
    </div>
</div>
@endsection
