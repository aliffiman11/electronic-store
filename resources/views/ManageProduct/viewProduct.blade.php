@extends('layouts.master')

@section('title', 'Home - My Store')

@section('content')
<style>
    /* Global Styling */
    body {
        background-color: #f8f9fa;
    }

    /* Outer Container Styling */
    .outer-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    /* Header Section */
    .header-section {
        background: linear-gradient(to right, #6a11cb, #2575fc);
        color: #ffffff;
        padding: 1rem;
        border-radius: 10px 10px 0 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .header-section h1 {
        font-size: 2.5rem;
        font-weight: bold;
    }

    .header-section p {
        font-size: 1.1rem;
        margin: 0;
    }

    /* Card Layout */
    .product-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 1px solid #ddd;
        border-radius: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
    }

    .card-img-top {
        object-fit: contain;
        height: 180px;
        background-color: #f8f9fa;
    }

    /* Footer Styling for Buttons */
    /* Buy Button */
    .btn-buy {
        width: 80%;
        background-color: #28a745;
        /* Green */
        color: white;
        font-weight: bold;
        padding: 8px 0;
        text-align: center;
        border-radius: 5px;
        transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
    }

    .btn-buy:hover {
        background-color: #218838;
        transform: scale(1.05);
    }

    /* Cart Icon */
    .action-icon {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #6a11cb;
        transition: transform 0.2s ease-in-out, color 0.3s;
        cursor: pointer;
    }

    .action-icon:hover {
        color: #4d0ca0;
        transform: scale(1.2);
    }


    /* Card Footer Alignment */
    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem;
    }
</style>

<!-- Outer Container -->
<div class="outer-container">
    <!-- Header Section -->
    <div class="header-section" style="
    background: #f8f9fa; 
    padding: 1.2rem; 
    text-align: center; 
    border-radius: 10px; 
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;">
        <h2 class="fw-bold" style="margin: 0; color: #333;">Our Products</h2>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">Browse our collection of amazing products!</p>
    </div>

    <!-- Product Grid -->
    <div class="row row-cols-1 row-cols-md-5 g-4">
        @foreach($products as $product)
        @if($product->stock == 'available') <!-- Display only available products -->
        <div class="col">
            <div class="product-card">
                <!-- Product Image -->
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/150' }}"
                    class="card-img-top" alt="{{ $product->name }}">

                <!-- Product Details -->
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-price fw-bold text-success">RM {{ number_format($product->price, 2) }}</p>
                </div>

                <!-- Card Footer -->
                <div class="card-footer d-flex justify-content-between align-items-center">
                    @guest
                    <!-- Guest Users -->
                    <a href="javascript:void(0)" onclick="showLoginAlert()" class="btn btn-buy btn-sm" style="width: 80%; text-align: center;">
                        BUY
                    </a>
                    <a href="javascript:void(0)" onclick="showLoginAlert()" class="action-icon" title="Add to Cart">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    @else
                    <!-- Authenticated Users -->
                    <a href="{{ route('checkout.form') }}?product_id={{ $product->id }}" class="btn btn-buy btn-sm" style="width: 80%; text-align: center;">
                        BUY
                    </a>
                    <button class="btn p-0 border-0 action-icon" title="Add to Cart" onclick="addToCart('{{ $product->id }}')">
                        <i class="fas fa-shopping-cart"></i>
                    </button>

                    <!-- Add to Cart Form -->
                    <form id="cartForm-{{ $product->id }}" action="{{ route('cart.add', $product->id) }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    @endguest
                </div>


            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>

<!-- JavaScript -->
<script>
    function showLoginAlert() {
        alert('Please log in to add products to the cart or buy.');
        window.location.href = "{{ route('login') }}";
    }

    function addToCart(productId) {
        // Submit the Add to Cart form for the specified product
        const formId = `cartForm-${productId}`;
        document.getElementById(formId).submit();
    }
</script>
@endsection