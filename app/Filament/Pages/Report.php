<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Report extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'filament.pages.report';

    public $totalRevenue;
    public $totalOrders;
    public $totalCustomers;
    public $recentOrders;
    public $topProducts;
    public $monthlyOrders;
    

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // 1. Total Earnings
        $this->totalRevenue = Order::sum('total_price');


        // 2. Total Orders
        $this->totalOrders = DB::table('orders')
            ->count();

        // 3. Total Customers
        $this->totalCustomers = DB::table('users')
            ->where('role', 'customer')
            ->count();

        // 4. Recent Orders (last 5 orders)
        $this->recentOrders = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->select(
                'users.name as customer_name',
                'orders.total_price',
                'orders.status',
                'orders.created_at'
            )
            ->orderBy('orders.created_at', 'desc')
            ->limit(5)
            ->get();


        // 5. Top Products (based on quantity sold)
        $this->topProducts = DB::table('orders')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(orders.quantity) as total_sold')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $this->monthlyOrders = Order::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_price) as revenue")
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get();
    }

    
}
