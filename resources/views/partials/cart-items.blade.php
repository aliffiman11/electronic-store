@if(!empty($cart))
    @php
        $totalPrice = 0;
    @endphp
    @foreach($cart as $id => $item)
        @php
            $totalPrice += $item['price'] * $item['quantity'];
        @endphp
        <div class="cart-item d-flex align-items-center mb-3">
            <div>
                <p class="mb-0 fw-bold">{{ $item['name'] }}</p>
                <small>RM {{ number_format($item['price'], 2) }}</small>
            </div>

            <div class="ms-auto d-flex align-items-center">
                <button class="btn btn-sm btn-outline-secondary me-1" onclick="updateCartItem('{{ $id }}', 'decrease')">-</button>
                <span class="fw-bold mx-2">{{ $item['quantity'] }}</span>
                <button class="btn btn-sm btn-outline-secondary" onclick="updateCartItem('{{ $id }}', 'increase')">+</button>
                <button class="btn btn-sm btn-danger ms-2" onclick="removeCartItem('{{ $id }}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    @endforeach
    <div class="total-price">
        <h5>Total: RM {{ number_format($totalPrice, 2) }}</h5>
    </div>
@else
    <p class="text-center">Your cart is empty.</p>
@endif
