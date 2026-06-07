<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\Order;
use App\Models\StockItem;
use Illuminate\Support\Facades\Auth;

class HospitalDashboardController extends Controller
{
    public function index()
    {
        $hospital = Hospital::where('user_id', Auth::id())->first();

        // Valeurs par défaut si pas d'hôpital associé
        $defaults = [
            'ordersCount'       => 0,
            'stockItems'        => 0,
            'pendingDeliveries' => 0,
            'criticalAlerts'    => 0,
            'recentOrders'      => collect(),
            'chartData'         => array_fill(0, 6, 0),
            'stockAlerts'       => collect(), // <-- toujours présent
            'hospital'          => null,
        ];

        if (!$hospital) {
            return view('hospital.dashboard', $defaults);
        }

        $ordersCount = Order::where('hospital_id', $hospital->id)
            ->whereMonth('created_at', now()->month)
            ->count();

        $stockItems = StockItem::where('hospital_id', $hospital->id)->count();

        $pendingDeliveries = Order::where('hospital_id', $hospital->id)
            ->whereIn('status', ['pending', 'validated', 'confirmed'])
            ->count();

        $criticalAlerts = StockItem::where('hospital_id', $hospital->id)
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->count();

        $recentOrders = Order::where('hospital_id', $hospital->id)
            ->with('supplier')
            ->latest()
            ->take(5)
            ->get();

        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $chartData[] = Order::where('hospital_id', $hospital->id)
                ->whereYear('created_at', now()->subMonths($i)->year)
                ->whereMonth('created_at', now()->subMonths($i)->month)
                ->count();
        }

        $stockAlerts = StockItem::where('hospital_id', $hospital->id)
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->orderBy('quantity')
            ->take(5)
            ->get();

        return view('hospital.dashboard', compact(
            'ordersCount',
            'stockItems',
            'pendingDeliveries',
            'criticalAlerts',
            'recentOrders',
            'chartData',
            'stockAlerts',
            'hospital'
        ));
    }
}