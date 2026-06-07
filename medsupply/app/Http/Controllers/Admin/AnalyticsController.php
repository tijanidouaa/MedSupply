<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Hospital;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // ── KPIs ──────────────────────────────────────────────
        $totalOrders    = Order::count();
        $totalHospitals = Hospital::count();
        $totalSuppliers = Supplier::count();

        // Dépenses totales du mois
        $depensesMois = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereNotIn('status', ['cancelled'])
            ->sum('total');

        // Commandes actives (pas livrées ni annulées)
        $commandesActives = Order::whereNotIn('status', ['delivered', 'cancelled'])->count();

        // Alertes critiques (fournisseurs non vérifiés)
        $alertes = Supplier::where('verified', false)->count();

        // ── Graphique dépenses mensuel/trimestriel/annuel ─────
        $depensesMensuelles = [];
        $labels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->isoFormat('MMM YY');
            $depensesMensuelles[] = round(Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->whereNotIn('status', ['cancelled'])
                ->sum('total'), 2);
        }

        // Trimestriel (4 derniers trimestres)
        $depensesTrimestrielles = [];
        $labelsT = [];
        for ($i = 3; $i >= 0; $i--) {
            $start = now()->startOfQuarter()->subQuarters($i);
            $end   = (clone $start)->endOfQuarter();
            $labelsT[] = 'T' . $start->quarter . ' ' . $start->year;
            $depensesTrimestrielles[] = round(Order::whereBetween('created_at', [$start, $end])
                ->whereNotIn('status', ['cancelled'])
                ->sum('total'), 2);
        }

        // Annuel (3 dernières années)
        $depensesAnnuelles = [];
        $labelsA = [];
        for ($i = 2; $i >= 0; $i--) {
            $year = now()->year - $i;
            $labelsA[] = (string) $year;
            $depensesAnnuelles[] = round(Order::whereYear('created_at', $year)
                ->whereNotIn('status', ['cancelled'])
                ->sum('total'), 2);
        }

        // ── Top produits commandés ────────────────────────────
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(8)
            ->get();

        // ── Taux de consommation par catégorie ────────────────
        $byCategory = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.category', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
            ->groupBy('products.category')
            ->orderByDesc('total_revenue')
            ->get();

        // ── Analyse comparative fournisseurs ──────────────────
        $supplierAnalysis = Supplier::withCount('orders')
            ->withSum('orders', 'total')
            ->with(['orders' => function ($q) {
                $q->select('supplier_id', 'status', DB::raw('AVG(DATEDIFF(updated_at, created_at)) as avg_days'))
                  ->groupBy('supplier_id', 'status');
            }])
            ->orderByDesc('orders_count')
            ->take(6)
            ->get()
            ->map(function ($supplier) {
                $delivered = $supplier->orders->where('status', 'delivered')->count();
                $total     = $supplier->orders_count ?: 1;
                $supplier->delivery_rate = round(($delivered / $total) * 100);
                $supplier->avg_delay = round($supplier->orders()
                    ->where('status', 'delivered')
                    ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg')
                    ->value('avg') ?? 0);
                return $supplier;
            });

        // ── Commandes par statut ──────────────────────────────
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')->get()
            ->mapWithKeys(fn($r) => [$r->status => $r->total]);

        // ── Top hôpitaux ──────────────────────────────────────
        $topHospitals = Hospital::withCount('orders')
            ->withSum('orders', 'total')
            ->orderByDesc('orders_count')
            ->take(5)
            ->get();

        return view('admin.analytics', compact(
            'totalOrders', 'totalHospitals', 'totalSuppliers',
            'depensesMois', 'commandesActives', 'alertes',
            'depensesMensuelles', 'labels',
            'depensesTrimestrielles', 'labelsT',
            'depensesAnnuelles', 'labelsA',
            'topProducts', 'byCategory',
            'supplierAnalysis', 'ordersByStatus', 'topHospitals'
        ));
    }
}