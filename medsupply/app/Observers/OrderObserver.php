<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockItem;

class OrderObserver
{
    /**
     * Déclenché quand le statut d'une commande change.
     * Si la commande passe en "delivered", on met à jour le stock de l'hôpital.
     */
    public function updated(Order $order): void
    {
        // On réagit uniquement quand le statut passe à "delivered"
        if ($order->wasChanged('status') && $order->status === 'delivered') {
            $this->updateHospitalStock($order);
        }
    }

    private function updateHospitalStock(Order $order): void
    {
        // Récupérer les items de la commande
        $items = OrderItem::where('order_id', $order->id)->get();

        foreach ($items as $item) {
            // Chercher si ce produit existe déjà dans le stock de l'hôpital
            $stockItem = StockItem::where('hospital_id', $order->hospital_id)
                ->where('product_id', $item->product_id)
                ->first();

            if ($stockItem) {
                // Incrémenter la quantité existante
                $stockItem->increment('quantity', $item->quantity);
            } else {
                // Créer une nouvelle entrée dans le stock
                $product = $item->product;
                StockItem::create([
                    'hospital_id' => $order->hospital_id,
                    'product_id'  => $item->product_id,
                    'name'        => $product->name ?? $item->name ?? 'Produit',
                    'category'    => $product->category ?? 'autres',
                    'quantity'    => $item->quantity,
                    'min_quantity'=> 10, // seuil par défaut
                    'price'       => $item->unit_price ?? $product->price ?? 0,
                ]);
            }
        }
    }
}