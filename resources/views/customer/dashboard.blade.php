@extends('layouts.master')

@section('title', 'Customer Dashboard')

<style>
    .pagination .page-link {
    color: #6a1e55;
    border: 1px solid #6a1e55;
    transition: all 0.3s ease-in-out;
}

.pagination .page-link:hover {
    background-color: #6a1e55;
    color: white;
}

.pagination .page-item.active .page-link {
    background-color: #6a1e55;
    border-color: #6a1e55;
    color: white;
}

</style>

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Your Invoices</h2>

    @if($orders->isEmpty())
    <p class="text-center text-muted">You have not made any purchases yet.</p>
    @else
    <div class="accordion" id="orderAccordion">
        @foreach($orders as $orderReference => $groupedOrders)
        @php
        $createdDate = $groupedOrders->first()->created_at->format('F d, Y'); // Get the creation date
        $totalItems = $groupedOrders->sum('quantity'); // Calculate total items
        $totalPrice = $groupedOrders->sum('total_price'); // Calculate total price
        @endphp
        <div class="card mb-3 shadow-sm">
            <!-- Card Header -->
            <div class="card-header bg-light" id="heading-{{ $orderReference }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 fw-bold">{{ $createdDate }}</p>
                        <p class="mb-0 text-muted">
                            {{ $totalItems }} item{{ $totalItems > 1 ? 's' : '' }} -
                            <strong>RM {{ number_format($totalPrice, 2) }}</strong>
                        </p>
                    </div>
                    <span class="badge bg-success">Paid</span>
                </div>
                <button class="btn btn-sm btn-outline-primary mt-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $orderReference }}" aria-expanded="false" aria-controls="collapse-{{ $orderReference }}">
                    View Details
                </button>
            </div>

            <!-- Card Body (Collapsible) -->
            <div id="collapse-{{ $orderReference }}" class="collapse" aria-labelledby="heading-{{ $orderReference }}" data-bs-parent="#orderAccordion">
                <div class="card-body">
                    <p><strong>Order Reference:</strong> {{ $orderReference }}</p>
                    <ul class="list-group">
                        @foreach($groupedOrders as $order)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                {{ $order->product->name ?? 'Product Not Found' }}
                                <small class="text-muted">(x{{ $order->quantity }})</small>
                            </span>
                            <span>RM {{ number_format($order->total_price, 2) }}</span>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Download Invoice Button -->
                    <div class="text-end mt-3">
                        <a href="{{ route('invoice.download', $orderReference) }}" class="btn btn-primary btn-sm">
                            Download Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Custom Pagination Links -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links('vendor.pagination.custom-pagination') }}
    </div>
    @endif
</div>
@endsection
