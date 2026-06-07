@extends('layouts.hospital')
@section('title', 'Mes Commandes')
@section('page-title', 'Mes Commandes')
@section('page-subtitle')
MedSupply · Suivi de vos commandes
@endsection

@section('content')

@php
    $pills  = ['pending'=>'pill-pending','validated'=>'pill-validated','confirmed'=>'pill-confirmed','delivered'=>'pill-delivered','cancelled'=>'pill-cancelled'];
    $labels = ['pending'=>'En attente','validated'=>'Validée','confirmed'=>'Confirmée','delivered'=>'Livrée','cancelled'=>'Annulée'];
@endphp

<div class="page-header">
    <div>
        <h2>Mes Commandes</h2>
        <p>{{ $orders->total() }} commande(s) au total</p>
    </div>
    <a href="{{ route('hospital.orders.create') }}" class="btn-ms-primary">
        <i class="fa-solid fa-plus"></i> Nouvelle commande
    </a>
</div>

<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:1rem;margin-bottom:1.4rem;">
    <div class="kpi-card">
        <div class="kpi-icon" style="background:var(--peach);color:var(--peach-dark);"><i class="fa-solid fa-clock"></i></div>
        <div class="kpi-label">En attente</div>
        <div class="kpi-value">{{ $counts['pending'] }}</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:var(--blue-soft);color:var(--blue-dark);"><i class="fa-solid fa-shield-halved"></i></div>
        <div class="kpi-label">Validée</div>
        <div class="kpi-value">{{ $counts['validated'] }}</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:var(--lavender);color:var(--lavender-dark);"><i class="fa-solid fa-box"></i></div>
        <div class="kpi-label">Confirmée</div>
        <div class="kpi-value">{{ $counts['confirmed'] }}</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:var(--mint);color:var(--mint-dark);"><i class="fa-solid fa-truck"></i></div>
        <div class="kpi-label">Livrée</div>
        <div class="kpi-value">{{ $counts['delivered'] }}</div>
    </div>
    <div class="kpi-card">
        {{-- Couleur distincte pour "Annulée" (rouge doux) --}}
        <div class="kpi-icon" style="background:#fde8e8;color:#b91c1c;"><i class="fa-solid fa-xmark"></i></div>
        <div class="kpi-label">Annulée</div>
        <div class="kpi-value">{{ $counts['cancelled'] }}</div>
    </div>
</div>

<div class="ms-card">
    <div class="ms-card-header">
        <span class="ms-card-title">
            <i class="fa-solid fa-file-invoice" style="color:var(--blue);margin-right:6px;"></i>
            Liste des commandes
        </span>
        <div class="search-box" style="width:200px">
            <i class="fa-solid fa-magnifying-glass" style="color:var(--text3);font-size:12px"></i>
            <input type="text" placeholder="Rechercher..." oninput="searchTable(this.value)">
        </div>
    </div>

    <table class="ms-table" id="ordTable">
        <thead>
            <tr>
                <th>Référence</th>
                <th>Fournisseur</th>
                <th>Statut</th>
                <th>Total</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr data-search="{{ strtolower($order->reference.' '.optional($order->supplier)->name.' '.($labels[$order->status] ?? $order->status).' '.$order->created_at->format('d/m/Y')) }}">
                <td style="font-weight:700;color:var(--blue-dark);font-family:monospace;font-size:0.82rem;">
                    {{ $order->reference }}
                </td>
                <td>{{ optional($order->supplier)->name ?? '—' }}</td>
                <td>
                    <span class="ms-pill {{ $pills[$order->status] ?? 'pill-pending' }}">
                        {{ $labels[$order->status] ?? $order->status }}
                    </span>
                </td>
                <td style="font-weight:600;">{{ number_format($order->total, 2) }} DH</td>
                <td style="color:var(--text3);font-size:0.8rem;">{{ $order->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('hospital.orders.show', $order->id) }}" class="icon-btn" title="Voir">
                        <i class="fa-solid fa-eye" style="font-size:12px;"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:3rem;color:var(--text3);">
                    <i class="fa-solid fa-inbox" style="font-size:2rem;display:block;margin-bottom:8px;opacity:0.4;"></i>
                    Aucune commande trouvée
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($orders->hasPages())
    <div style="padding:1rem 1.4rem;border-top:1px solid var(--border);">
        {{ $orders->links() }}
    </div>
    @endif
</div>

<style>
.pill-pending   { background:#fff3cd; color:#856404; }
.pill-validated { background:var(--blue-soft); color:var(--blue-dark); }
.pill-confirmed { background:var(--lavender); color:var(--lavender-dark); }
.pill-delivered { background:var(--mint); color:var(--mint-dark); }
.pill-cancelled { background:#fde8e8; color:#b91c1c; }
</style>
@endsection

@section('scripts')
<script>
function searchTable(q) {
    q = q.toLowerCase().trim();
    document.querySelectorAll('#ordTable tbody tr').forEach(r => {
        r.style.display = r.dataset.search?.includes(q) ? '' : 'none';
    });
}
</script>
@endsection