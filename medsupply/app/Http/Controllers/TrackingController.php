<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\SeventeenTrackService;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    protected $tracker;

    public function __construct(SeventeenTrackService $tracker)
    {
        $this->tracker = $tracker;
    }

    public function show($orderId)
    {
        $order = Order::with('supplier')->findOrFail($orderId);

        $tracking    = null;
        $checkpoints = [];
        $error       = null;

        if ($order->tracking_number) {
            try {
                $data     = $this->tracker->getTracking($order->tracking_number);
                $accepted = $data['data']['accepted'][0]['track'] ?? null;

                if ($accepted) {
                    $tracking    = $accepted['z0'] ?? null; // infos générales
                    $checkpoints = $accepted['z1'] ?? [];   // historique des étapes
                } else {
                    $error = 'Numéro de tracking non trouvé sur 17Track.';
                }
            } catch (\Exception $e) {
                $error = 'Impossible de contacter le service de tracking.';
            }
        }

        return view('tracking.show', compact('order', 'tracking', 'checkpoints', 'error'));
    }

    public function update(Request $request, $orderId)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:100',
            'carrier'         => 'nullable|string|max:100',
        ]);

        $order = Order::findOrFail($orderId);
        $order->tracking_number = $request->tracking_number;
        $order->carrier         = $request->carrier;
        $order->save();

        // Enregistrer sur 17Track
        try {
            $this->tracker->registerTracking($request->tracking_number);
        } catch (\Exception $e) {
            // On continue même si 17Track échoue
        }

        return redirect()->route('hospital.orders.tracking', $orderId)
                         ->with('success', 'Numéro de tracking enregistré avec succès.');
    }
}