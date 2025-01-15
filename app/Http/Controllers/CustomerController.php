<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\LengthAwarePaginator;


class CustomerController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id(); // Ensure the user is logged in
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to access your dashboard.');
        }

        // Retrieve all orders grouped by order_reference
        $orders = Order::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('order_reference');

        // Pagination logic
        $currentPage = LengthAwarePaginator::resolveCurrentPage(); // Get the current page number
        $perPage = 5; // Number of orders per page
        $paginatedOrders = new LengthAwarePaginator(
            $orders->forPage($currentPage, $perPage), // Slice the orders for the current page
            $orders->count(), // Total number of orders
            $perPage, // Orders per page
            $currentPage, // Current page
            ['path' => LengthAwarePaginator::resolveCurrentPath()] // Preserve the current path
        );

        return view('customer.dashboard', ['orders' => $paginatedOrders]);
    }

    public function downloadInvoice($orderReference)
    {
        // Retrieve all orders for the given order reference
        $orders = Order::where('order_reference', $orderReference)->get();

        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'Invoice not found.');
        }

        // Calculate total items and total price
        $totalItems = $orders->sum('quantity');
        $totalPrice = $orders->sum('total_price');
        $createdDate = $orders->first()->created_at->format('F d, Y');

        // Generate the PDF
        $pdf = Pdf::loadView('customer.invoice-template', compact('orders', 'totalItems', 'totalPrice', 'createdDate', 'orderReference'));

        // Return the PDF as a download
        return $pdf->download("Invoice_$orderReference.pdf");
    }
}
