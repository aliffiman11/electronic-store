@extends('layouts.master')

@section('title', 'Home - My Store')

@section('content')
<style>
    /* Global Styling */
    body {
        background-color: #f8f9fa;
    }

    /* Hero Section Styling */
    .hero-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem;
        background: linear-gradient(135deg, #6a11cb, #2575fc);
        border-radius: 10px;
        color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .hero-text h1 {
        font-size: 2.5rem;
        font-weight: bold;
    }

    .hero-text p {
        margin: 1rem 0;
        font-size: 1.1rem;
    }

    .btn-shop-now {
        display: inline-block;
        padding: 0.8rem 1.5rem;
        font-size: 1.2rem;
        color: #fff;
        background: #28a745;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-shop-now:hover {
        background: #218838;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .hero-image img {
        max-width: 100%;
        border-radius: 10px;
    }

    /* Featured Products Section Styling */
    .featured-products-section {
        margin-top: 2rem;
    }

    .featured-products-section h2 {
        font-weight: bold;
        margin-bottom: 1.5rem;
        text-align: center;
    }

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
        border-bottom: 1px solid #ddd;
    }

    /* Footer Styling for Buttons */
    .btn-buy {
        width: 80%;
        background-color: #28a745;
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

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem;
        border-top: 1px solid #ddd;
    }

    /* General Styles */
    body {
        background-color: #f8f9fa;
    }

    .outer-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .text-center {
        text-align: center;
    }

    .text-success {
        color: #28a745;
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-text">
        <h1>Top Quality Electrical Products for Your Home</h1>
        <p>Discover a wide range of reliable electrical appliances to power your lifestyle with ease and efficiency.</p>
        <a href="{{ url('/products') }}" class="btn-shop-now">Shop Now</a>
    </div>
    <div class="hero-image">
        <img src="{{ asset('images/homepage.webp') }}" alt="Hero Image" class="img-fluid rounded">
    </div>
</div>

<!-- Featured Products Section -->
<div class="featured-products-section mt-5">
    <h2 class="text-center">Featured Products</h2>
    <div class="row row-cols-1 row-cols-md-4 g-4 mt-3">
        @foreach($products as $product)
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