<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Order;

class SupplierInvoiceController extends Controller
{
    public function index()
    {
        $invoices = Order::where('status', 'delivered')
            ->with('hospital')
            ->latest()
            ->paginate(15);
        return view('supplier.invoices.index', compact('invoices'));
    }
}