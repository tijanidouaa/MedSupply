<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Hospital;
use App\Models\Supplier;
use App\Models\StockItem;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'hospitalsCount'   => Hospital::where('is_active', true)->count(),
            'ordersThisMonth'  => Order::whereMonth('created_at', now()->month)->count(),
            'suppliersCount'   => Supplier::count(),
            'suppliersWaiting' => 0,
            'criticalAlerts'   => 0,
            'recentOrders'     => Order::latest()->take(5)->get(),
            'topSuppliers'     => Supplier::latest()->take(4)->get(),
            'notifications'    => collect(),
        ];

        return view('admin.dashboard', $data);
    }
}