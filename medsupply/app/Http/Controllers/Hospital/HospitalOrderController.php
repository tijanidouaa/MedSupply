<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Hospital;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HospitalOrderController extends Controller
{
    private function getHospital(): ?Hospital
    {
        return Hospital::where('user_id', Auth::id())->first();
    }

    public function index()
    {
        $hospital = $this->getHospital();

        if (!$hospital) {
            return view('hospital.orders.index', [
                'orders' => collect(),
                'counts' => array_fill_keys(['pending','validated','confirmed','delivered','cancelled'], 0),
            ]);
        }

        $orders = Order::where('hospital_id', $hospital->id)
            ->with('supplier')
            ->latest()
            ->paginate(15);

        $counts = [];
        foreach (['pending','validated','confirmed','delivered','cancelled'] as $status) {
            $counts[$status] = Order::where('hospital_id', $hospital->id)
                ->where('status', $status)->count();
        }

        return view('hospital.orders.index', compact('orders', 'counts'));
    }

    public function show($id)
    {
        $hospital = $this->getHospital();
        $order = Order::where('hospital_id', $hospital?->id)
                      ->with(['supplier', 'items.product'])
                      ->findOrFail($id);
        return view('hospital.orders.show', compact('order'));
    }

    public function create()
    {
        $suppliers = Supplier::where('verified', true)->get();

        $productsBySupplier = [];
        $products = Product::where('stock', '>', 0)->get();
        foreach ($products as $p) {
            $productsBySupplier[$p->supplier_id][] = [
                'id'    => $p->id,
                'name'  => $p->name,
                'price' => (float) $p->price,
                'stock' => (int) $p->stock,
            ];
        }

        return view('hospital.orders.create', compact('suppliers', 'productsBySupplier'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'        => 'required|exists:suppliers,id',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $hospital = $this->getHospital();

        if (!$hospital) {
            return back()->withErrors(['error' => 'Aucun hôpital associé à votre compte.']);
        }

        $total = collect($request->items)->sum(function ($item) {
            return ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
        });

        $reference = 'ORD-' . date('Y') . '-' . strtoupper(substr(uniqid(), -5));

        $order = Order::create([
            'hospital_id' => $hospital->id,
            'supplier_id' => $request->supplier_id,
            'reference'   => $reference,
            'status'      => 'pending',
            'total'       => $total,
        ]);

        // ✅ Enregistrer les items
        foreach ($request->items as $item) {
            $qty      = (int) $item['quantity'];
            $price    = (float) $item['unit_price'];
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['product_id'],
                'quantity'   => $qty,
                'unit_price' => $price,
                'subtotal'   => $qty * $price,
            ]);
        }

        try {
            Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($order));
        } catch (\Exception $e) {
            \Log::error('Mail commande échoué : ' . $e->getMessage());
        }

        return redirect()->route('hospital.orders.index')
                         ->with('success', 'Commande ' . $reference . ' créée avec succès.');
    }
}