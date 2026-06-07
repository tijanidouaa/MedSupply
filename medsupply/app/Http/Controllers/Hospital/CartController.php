<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmationMail;
use App\Models\CartItem;
use App\Models\Hospital;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    private function getHospital(): ?Hospital
    {
        return Hospital::where('user_id', Auth::id())->first();
    }

    // Afficher le panier
    public function index()
    {
        $hospital = $this->getHospital();

        if (!$hospital) {
            return view('hospital.cart.index', ['groups' => collect(), 'totalHt' => 0, 'totalTtc' => 0, 'totalItems' => 0]);
        }

        $items = CartItem::where('hospital_id', $hospital->id)
            ->with(['product', 'supplier'])
            ->get();

        // Grouper par fournisseur
        $groups   = $items->groupBy('supplier_id');
        $totalHt  = $items->sum('subtotal');
        $totalTtc = $items->sum('subtotal_ttc');
        $totalItems = $items->sum('quantity');

        return view('hospital.cart.index', compact('groups', 'totalHt', 'totalTtc', 'totalItems'));
    }

    // Ajouter un produit au panier
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $hospital = $this->getHospital();
        if (!$hospital) {
            return back()->withErrors(['error' => 'Aucun hôpital associé.']);
        }

        $product = Product::findOrFail($request->product_id);

        // Ajouter ou incrémenter
        $existing = CartItem::where('hospital_id', $hospital->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'hospital_id' => $hospital->id,
                'product_id'  => $product->id,
                'supplier_id' => $product->supplier_id,
                'quantity'    => $request->quantity,
                'unit_price'  => $product->price,
                'discount'    => 0,
            ]);
        }

        return back()->with('success', '« ' . $product->name . ' » ajouté au panier.');
    }

    // Mettre à jour la quantité
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0|max:100',
        ]);

        $hospital = $this->getHospital();
        $item = CartItem::where('hospital_id', $hospital->id)->findOrFail($itemId);

        $item->update([
            'quantity' => $request->quantity,
            'discount' => $request->discount ?? $item->discount,
        ]);

        return back()->with('success', 'Panier mis à jour.');
    }

    // Supprimer un article
    public function remove($itemId)
    {
        $hospital = $this->getHospital();
        CartItem::where('hospital_id', $hospital->id)->findOrFail($itemId)->delete();

        return back()->with('success', 'Article retiré du panier.');
    }

    // Vider le panier
    public function clear()
    {
        $hospital = $this->getHospital();
        CartItem::where('hospital_id', $hospital->id)->delete();

        return back()->with('success', 'Panier vidé.');
    }

    // Convertir le panier en commande(s) — une commande par fournisseur
    public function checkout()
    {
        $hospital = $this->getHospital();

        if (!$hospital) {
            return back()->withErrors(['error' => 'Aucun hôpital associé.']);
        }

        $items = CartItem::where('hospital_id', $hospital->id)
            ->with(['product', 'supplier'])
            ->get();

        if ($items->isEmpty()) {
            return back()->withErrors(['error' => 'Votre panier est vide.']);
        }

        $orders = [];

        DB::transaction(function () use ($items, $hospital, &$orders) {
            // Grouper par fournisseur → une commande par fournisseur
            $groups = $items->groupBy('supplier_id');

            foreach ($groups as $supplierId => $groupItems) {
                $totalHt = $groupItems->sum('subtotal');

                // Référence format ORD-2026-XXXXX
                $reference = 'ORD-' . date('Y') . '-' . strtoupper(substr(uniqid(), -5));

                $order = Order::create([
                    'hospital_id' => $hospital->id,
                    'supplier_id' => $supplierId,
                    'reference'   => $reference,
                    'status'      => 'pending',
                    'total'       => $totalHt,
                ]);

                $orders[] = $order;

                // Envoyer le mail de confirmation
                try {
                    Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($order));
                } catch (\Exception $e) {
                    \Log::error('Mail commande échoué : ' . $e->getMessage());
                }
            }

            // Vider le panier après checkout
            CartItem::where('hospital_id', $hospital->id)->delete();
        });

        $count = count($orders);
        return redirect()->route('hospital.orders.index')
            ->with('success', $count . ' commande(s) créée(s) avec succès. Un email de confirmation vous a été envoyé.');
    }
}