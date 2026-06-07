<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Supplier;
use App\Services\SeventeenTrackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierOrderController extends Controller
{
    private function getSupplier(): ?Supplier
    {
        return Supplier::where('user_id', Auth::id())->first();
    }

    public function index()
    {
        $supplier = $this->getSupplier();

        if (!$supplier) {
            return view('supplier.orders.index', ['orders' => collect()]);
        }

        $orders = Order::where('supplier_id', $supplier->id)
            ->with('hospital')
            ->latest()
            ->paginate(15);

        return view('supplier.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $supplier = $this->getSupplier();

        $order = Order::where('supplier_id', $supplier?->id)
            ->with('hospital')
            ->findOrFail($id);

        return view('supplier.orders.show', compact('order'));
    }

    // Avancer le statut de la commande
    public function validate(Request $request, $id)
    {
        $supplier = $this->getSupplier();
        $order    = Order::where('supplier_id', $supplier?->id)->findOrFail($id);

        $newStatus = $request->input('new_status');

        $allowed = [
            'pending'   => ['confirmed', 'cancelled'],
            'validated' => ['confirmed', 'shipped', 'cancelled'],
            'confirmed' => ['shipped', 'cancelled'],
            'shipped'   => ['delivered'],
        ];

        if (!isset($allowed[$order->status]) || !in_array($newStatus, $allowed[$order->status])) {
            return back()->withErrors(['error' => 'Transition de statut non autorisée.']);
        }

        $order->status = $newStatus;

        if ($newStatus === 'shipped') {
            $order->shipped_at = now();
        }
        if ($newStatus === 'delivered') {
            $order->delivered_at = now();
        }

        $order->save();

        $labels = [
            'confirmed' => 'confirmée',
            'shipped'   => 'marquée comme expédiée',
            'delivered' => 'marquée comme livrée',
            'cancelled' => 'annulée',
        ];

        return back()->with('success', 'Commande ' . ($labels[$newStatus] ?? $newStatus) . ' avec succès.');
    }

    // Enregistrer tracking + société de livraison
    public function updateTracking(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'nullable|string|max:100',
            'carrier'         => 'required|string|max:100',
        ]);

        $supplier = $this->getSupplier();
        $order    = Order::where('supplier_id', $supplier?->id)->findOrFail($id);

        $order->carrier         = $request->carrier;
        $order->tracking_number = $request->tracking_number;
        $order->save();

        // Enregistrer sur 17Track si numéro fourni
        if ($request->tracking_number) {
            try {
                app(SeventeenTrackService::class)->registerTracking($request->tracking_number);
            } catch (\Exception $e) {
                \Log::error('17Track register failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Informations de livraison enregistrées.');
    }
}