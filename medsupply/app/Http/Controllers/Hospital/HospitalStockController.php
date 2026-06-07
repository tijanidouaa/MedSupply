<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Hospital;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HospitalStockController extends Controller
{
    private function getHospital(): ?Hospital
    {
        return Hospital::where('user_id', Auth::id())->first();
    }

    // Liste du stock
    public function index()
    {
        $hospital = $this->getHospital();

        $stocks = StockItem::where('hospital_id', $hospital?->id)
            ->with('product.supplier')
            ->latest()
            ->paginate(20);

        $allItems     = StockItem::where('hospital_id', $hospital?->id)->get();
        $criticalCount = $allItems->filter(fn($i) => $i->quantity > 0 && $i->quantity <= $i->min_quantity)->count();
        $outCount      = $allItems->filter(fn($i) => $i->quantity === 0)->count();
        $okCount       = $allItems->filter(fn($i) => $i->quantity > $i->min_quantity)->count();

        return view('hospital.stock.index', compact('stocks', 'criticalCount', 'outCount', 'okCount'));
    }

    // Ajouter un produit au stock manuellement
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'category'     => 'required|in:consommables,medicaments,equipements,autres',
            'quantity'     => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:1',
            'price'        => 'required|numeric|min:0',
        ]);

        $hospital = $this->getHospital();

        StockItem::create([
            'hospital_id'  => $hospital->id,
            'name'         => $request->name,
            'category'     => $request->category,
            'quantity'     => $request->quantity,
            'min_quantity' => $request->min_quantity,
            'price'        => $request->price,
        ]);

        return back()->with('success', 'Produit ajouté au stock.');
    }

    // Modifier quantité et seuil
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity'     => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:1',
        ]);

        $hospital = $this->getHospital();
        $item = StockItem::where('hospital_id', $hospital?->id)->findOrFail($id);

        $item->update([
            'quantity'     => $request->quantity,
            'min_quantity' => $request->min_quantity,
        ]);

        return back()->with('success', 'Stock mis à jour.');
    }

    // Supprimer un produit du stock
    public function destroy($id)
    {
        $hospital = $this->getHospital();
        StockItem::where('hospital_id', $hospital?->id)->findOrFail($id)->delete();

        return back()->with('success', 'Produit supprimé du stock.');
    }

    // Catalogue
    public function catalog()
    {
        $products  = Product::with('supplier')->latest()->get();
        $hospital  = $this->getHospital();
        $cartCount = $hospital
            ? CartItem::where('hospital_id', $hospital->id)->sum('quantity')
            : 0;

        $comparisons = Product::with('supplier')
            ->get()
            ->groupBy('name')
            ->filter(fn($group) => $group->count() > 1)
            ->flatMap(function ($group) {
                $minPrice = $group->min('price');
                return $group->map(function ($product) use ($minPrice) {
                    $product->is_cheapest = ($product->price == $minPrice);
                    return $product;
                });
            });

        return view('hospital.catalog.index', compact('products', 'cartCount', 'comparisons'));
    }

    // Alertes stock
    public function alerts()
    {
        $hospital = $this->getHospital();

        $criticalStock = StockItem::where('hospital_id', $hospital?->id)
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->where('quantity', '>', 0)
            ->with('product')
            ->get();

        $outOfStock = StockItem::where('hospital_id', $hospital?->id)
            ->where('quantity', 0)
            ->with('product')
            ->get();

        $lateOrders = Order::where('hospital_id', $hospital?->id)
            ->whereIn('status', ['validated', 'confirmed'])
            ->where('created_at', '<=', now()->subDays(7))
            ->with('supplier')
            ->get();

        $lowSupplierStock = Product::where('stock', '>', 0)
            ->where('stock', '<', 5)
            ->with('supplier')
            ->get();

        $totalAlerts = $criticalStock->count() + $outOfStock->count() + $lateOrders->count();

        return view('hospital.alerts.index', compact(
            'criticalStock', 'outOfStock', 'lateOrders', 'lowSupplierStock', 'totalAlerts'
        ));
    }
}