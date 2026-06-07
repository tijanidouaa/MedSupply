<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['hospital', 'supplier'])->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['hospital', 'supplier'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|string',
        ]);

        $order->update($request->only('status'));

        return redirect()->route('admin.orders.index')
                         ->with('success', 'Commande mise à jour.');
    }

    public function destroy($id)
    {
        Order::findOrFail($id)->delete();

        return redirect()->route('admin.orders.index')
                         ->with('success', 'Commande supprimée.');
    }
    public function validateOrder($id)
    {
    Order::findOrFail($id)->update(['status' => 'validated_admin']);
    return back()->with('success', 'Commande validée.');
    }

public function invoice($id)
    {
    $order = Order::with(['hospital', 'supplier'])->findOrFail($id);
    return view('admin.orders.invoice', compact('order'));
    }
}