<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Store')</title>

    @vite(['resources/js/app.js']) <!-- Include Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        /* General Page Styling */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        main {
            flex: 1;
        }

        /* Navbar Custom Styling */
        nav.navbar {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1020;
        }

        nav .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: #fff;
            transition: color 0.3s ease;
        }

        nav .navbar-brand:hover {
            color: #ffd700;
        }

        nav .nav-link {
            color: #fff;
            font-size: 1rem;
            position: relative;
            transition: color 0.3s ease;
        }

        nav .nav-link:hover {
            color: #ffd700;
        }

        nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #ffd700;
            transition: width 0.3s ease;
        }

        nav .nav-link:hover::after {
            width: 100%;
        }

        nav .dropdown-menu {
            background: #fff;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            animation: fadeIn 0.3s ease-in-out;
        }

        nav .dropdown-item {
            color: #333;
            transition: background 0.2s ease, color 0.2s ease;
        }

        nav .dropdown-item:hover {
            background: #6a11cb;
            color: #fff;
        }

        /* Floating Cart Icon Styling */
        .floating-cart {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            font-size: 1.5rem;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: background 0.3s ease;
        }

        .floating-cart:hover {
            background: #ffd700;
            color: #333;
        }

        /* Sidebar Cart Styling */
        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -100%;
            width: 300px;
            height: 100%;
            background: #fff;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 1050;
            transition: right 0.3s ease;
        }

        .cart-sidebar.active {
            right: 0;
        }

        .cart-sidebar .header {
            background: #6a11cb;
            color: #fff;
            padding: 10px 15px;
            font-size: 1.2rem;
            font-weight: bold;
            text-align: center;
        }

        .cart-sidebar .cart-content {
            padding: 15px;
            overflow-y: auto;
            max-height: calc(100% - 60px);
        }

        .cart-sidebar .close-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 1.2rem;
            cursor: pointer;
            color: #fff;
        }

        /* Footer Styling */
        footer {
            background: #1A1A1D;
            color: #ccc;
            padding: 20px 0;
            text-align: center;
        }

        footer .social-icons a {
            color: #ccc;
            margin: 0 10px;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        footer .social-icons a:hover {
            color: #ffd700;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">MyStore</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/products') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                    </li>
                </ul>

                <!-- Authentication Links -->
                <ul class="navbar-nav ms-auto">
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                    @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </ul>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar Cart -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="header">
            <span class="close-btn" onclick="toggleCart()">&times;</span>
            My Cart
        </div>
        <div id="cartContent" class="cart-content">
            @include('partials.cart-items', ['cart' => session('cart', [])])
        </div>
        <div class="cart-footer text-center mt-4">
            <form action="{{ route('checkout.form') }}" method="GET">
                @csrf
                <button type="submit" class="btn btn-primary">Checkout Cart</button>
            </form>
        </div>

    </div>

    <!-- Floating Cart Icon -->
    <div class="floating-cart" onclick="toggleCart()">
        <i class="fas fa-shopping-cart"></i>
    </div>

    <!-- Main Content -->
    <main class="container mt-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p>&copy; 2024 MyStore. All Rights Reserved.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <script>
        function toggleCart() {
            const cartSidebar = document.getElementById('cartSidebar');
            cartSidebar.classList.toggle('active');

            // Fetch cart items dynamically when opening the sidebar
            if (cartSidebar.classList.contains('active')) {
                fetchCartItems();
            }
        }

        function fetchCartItems() {
            fetch('/cart/items', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then((response) => response.json())
                .then((data) => {
                    document.getElementById('cartContent').innerHTML = data.html;
                })
                .catch((error) => console.error('Error fetching cart items:', error));
        }

        function updateCartItem(productId, action) {
            fetch(`/cart/update/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        action: action
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('cartContent').innerHTML = data.html;
                        document.getElementById('totalPrice').innerText = `RM ${data.totalPrice.toFixed(2)}`;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error updating cart item:', error));
        }

        function removeCartItem(productId) {
            fetch(`/cart/delete/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('cartContent').innerHTML = data.html;
                        document.getElementById('totalPrice').innerText = `RM ${data.totalPrice.toFixed(2)}`;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error removing cart item:', error));
        }

        function addToCart(productId) {
            fetch(`/cart/add/${productId}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart sidebar dynamically
                        document.getElementById("cartContent").innerHTML = data.html;
                        document.getElementById("totalPrice").innerText = `RM ${data.totalPrice.toFixed(2)}`;
                        alert(data.message); // Optional: Show success message
                    } else {
                        alert(data.message || "Failed to add product to cart.");
                    }
                })
                .catch(error => console.error("Error adding to cart:", error));
        }
    </script>

</body>

</html>