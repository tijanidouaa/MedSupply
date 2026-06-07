<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class SupplierDeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Order::where('status', 'delivered')
            ->with('hospital')
            ->latest()
            ->paginate(15);
        return view('supplier.deliveries.index', compact('deliveries'));
    }
}