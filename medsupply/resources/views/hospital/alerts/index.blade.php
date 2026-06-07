@extends('layouts.hospital')
@section('title', 'Alertes Stock')
@section('page-title', 'Alertes Stock')
@section('page-subtitle')
MedSupply · Stocks critiques
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Alertes Stock</h2>
        <p>{{ $totalAlerts }} alerte(s) active(s)</p>
    </div>
</div>

@if($totalAlerts > 0)
<div style="background:var(--peach);border:1.5px solid var(--peach-dark);border-radius:var(--radius);padding:12px 16px;margin-bottom:1.4rem;display:flex;align-items:center;gap:10px;">
    <i class="fa-solid fa-triangle-exclamation" style="color:var(--peach-dark);font-size:1.1rem;"></i>
    <span style="font-size:0.84rem;font-weight:600;color:var(--peach-dark);">{{ $totalAlerts }} alerte(s) nécessitent votre attention !</span>
</div>
@endif

{{-- Ruptures totales --}}
@if($outOfStock->count() > 0)
<div class="ms-card" style="margin-bottom:1.4rem;border-color:rgba(224,80,80,0.3);">
    <div class="ms-card-header">
        <span class="ms-card-title" style="color:#e05d5d;">
            <i class="fa-solid fa-circle-xmark" style="margin-right:6px;"></i>
            Ruptures totales ({{ $outOfStock->count() }})
        </span>
    </div>
    <table class="ms-table">
        <thead>
            <tr><th>Produit</th><th>Catégorie</th><th>Stock minimum</th><th>Action</th></tr>
        </thead>
        <tbody>
            @foreach($outOfStock as $item)
            <tr>
                <td style="font-weight:600;">{{ $item->name }}</td>
                <td><span class="ms-pill" style="background:var(--peach);color:var(--peach-dark);">{{ $item->category }}</span></td>
                <td style="color:var(--text3);">{{ $item->min_quantity }} unités</td>
                <td>
                    <a href="{{ route('hospital.orders.create') }}" class="btn-ms-primary" style="padding:5px 12px;font-size:0.76rem;">
                        <i class="fa-solid fa-cart-plus"></i> Commander
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- Stocks critiques --}}
<div class="ms-card" style="margin-bottom:1.4rem;">
    <div class="ms-card-header">
        <span class="ms-card-title" style="color:var(--peach-dark);">
            <i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i>
            Stocks critiques ({{ $criticalStock->count() }})
        </span>
    </div>
    <table class="ms-table">
        <thead>
            <tr><th>Produit</th><th>Quantité actuelle</th><th>Minimum requis</th><th>Manque</th><th>Action</th></tr>
        </thead>
        <tbody>
            @forelse($criticalStock as $item)
            <tr>
                <td style="font-weight:600;">{{ $item->name }}</td>
                <td><span style="font-weight:700;color:var(--peach-dark);">{{ $item->quantity }}</span></td>
                <td style="color:var(--text3);">{{ $item->min_quantity }}</td>
                <td><span style="font-weight:700;color:var(--peach-dark);">{{ max(0, $item->min_quantity - $item->quantity) }} unité(s)</span></td>
                <td>
                    <a href="{{ route('hospital.orders.create') }}" class="btn-ms-primary" style="padding:5px 12px;font-size:0.76rem;">
                        <i class="fa-solid fa-cart-plus"></i> Commander
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:2rem;color:var(--text3);">
                    <i class="fa-solid fa-circle-check" style="font-size:2rem;display:block;margin-bottom:8px;color:var(--mint-dark);"></i>
                    Aucun stock critique
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Commandes en retard --}}
@if($lateOrders->count() > 0)
<div class="ms-card" style="margin-bottom:1.4rem;">
    <div class="ms-card-header">
        <span class="ms-card-title" style="color:#f5a623;">
            <i class="fa-solid fa-clock" style="margin-right:6px;"></i>
            Commandes en retard ({{ $lateOrders->count() }})
        </span>
    </div>
    <table class="ms-table">
        <thead>
            <tr><th>Référence</th><th>Fournisseur</th><th>Statut</th><th>Date commande</th></tr>
        </thead>
        <tbody>
            @foreach($lateOrders as $order)
            <tr>
                <td style="font-family:monospace;font-size:0.82rem;color:var(--blue-dark);">{{ $order->reference }}</td>
                <td>{{ optional($order->supplier)->name ?? '—' }}</td>
                <td><span class="ms-pill pill-pending">{{ $order->status }}</span></td>
                <td style="color:var(--text3);font-size:0.8rem;">{{ $order->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- Si tout est OK --}}
@if($totalAlerts === 0)
<div style="text-align:center;padding:4rem;color:var(--text3);">
    <i class="fa-solid fa-circle-check" style="font-size:3rem;display:block;margin-bottom:12px;color:var(--mint-dark);"></i>
    <div style="font-size:1rem;font-weight:600;color:var(--text);">Tout est en ordre !</div>
    <div style="font-size:0.84rem;margin-top:4px;">Aucune alerte stock active.</div>
</div>
@endif

@endsection