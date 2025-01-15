<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public $totalUsers;
    public $totalProducts;
    public $totalRevenue;
    public $ordersToday;
    public $salesData;
    public $totalRevenueToday;
    public $hourlyData;


    public function getListeners()
    {
        return ['echo:orders,new-order' => 'refreshData'];
    }


    public function mount()
    {
        // Load initial data
        $this->loadData();
    }

    public function refreshData()
    {
        logger('New order event received in Dashboard.');
        $this->loadData(); // Reload data
    }



    private function loadData()
{
    $this->totalUsers = User::count();
    $this->totalProducts = Product::count();
    $this->totalRevenue = Order::sum('total_price');
    $this->ordersToday = Order::whereDate('created_at', today())->count();
    $this->totalRevenueToday = Order::whereDate('created_at', today())->sum('total_price');

    // Fetch hourly orders and revenue for today's chart
    $this->hourlyData = Order::selectRaw("HOUR(created_at) as hour, COUNT(*) as orders, SUM(total_price) as revenue")
        ->whereDate('created_at', today())
        ->groupByRaw("HOUR(created_at)")
        ->orderByRaw("HOUR(created_at)")
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item->hour => ['orders' => $item->orders, 'revenue' => $item->revenue]];
        })
        ->toArray();
}

}
