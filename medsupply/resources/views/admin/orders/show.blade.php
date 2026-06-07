@extends('layouts.admin')
@section('title', 'Détail Commande')
@section('page-title', 'Détail Commande')
@section('page-subtitle')
{{ $order->reference }} · Fiche complète
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Commande {{ $order->reference }}</h2>
        <p>Créée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn-ms-secondary">
            <i class="fa-solid fa-file-pdf"></i> Facture PDF
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn-ms-secondary">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.4rem;margin-bottom:1.4rem;">

    <!-- Infos commande -->
    <div class="ms-card">
        <div class="ms-card-header">
            <span class="ms-card-title"><i class="fa-solid fa-file-invoice" style="color:var(--blue);margin-right:8px;"></i>Informations commande</span>
        </div>
        <div class="ms-card-body">
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--border);">
                    <span style="font-size:0.8rem;color:var(--text3);">Référence</span>
                    <span style="font-family:monospace;font-weight:600;color:var(--blue-dark);">{{ $order->reference }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--border);">
                    <span style="font-size:0.8rem;color:var(--text3);">Statut</span>
                    @php
                        $statusMap = [
                            'pending'   => ['#fff3cd','#856404','En attente'],
                            'validated' => ['var(--blue-soft)','var(--blue-dark)','Validé'],
                            'confirmed' => ['var(--lavender)','var(--lavender-dark)','Confirmé'],
                            'shipped'   => ['#ede9fe','#6d28d9','Expédié'],
                            'delivered' => ['var(--mint)','var(--mint-dark)','Livré'],
                            'cancelled' => ['var(--peach)','var(--peach-dark)','Annulé'],
                        ];
                        $s = $statusMap[$order->status] ?? ['var(--bg)','var(--text)','Inconnu'];
                    @endphp
                    <span class="ms-pill" style="background:{{ $s[0] }};color:{{ $s[1] }};">{{ $s[2] }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--border);">
                    <span style="font-size:0.8rem;color:var(--text3);">Total HT</span>
                    <span style="font-weight:700;font-size:1rem;">{{ number_format($order->total, 2) }} MAD</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--border);">
                    <span style="font-size:0.8rem;color:var(--text3);">TVA (20%)</span>
                    <span style="font-weight:600;">{{ number_format($order->total * 0.2, 2) }} MAD</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="font-size:0.8rem;color:var(--text3);">Total TTC</span>
                    <span style="font-weight:700;font-size:1.1rem;color:var(--lavender-dark);">{{ number_format($order->total * 1.2, 2) }} MAD</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Hôpital + Fournisseur -->
    <div style="display:flex;flex-direction:column;gap:1rem;">
        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title"><i class="fa-solid fa-hospital" style="color:var(--blue);margin-right:8px;"></i>Hôpital</span>
            </div>
            <div class="ms-card-body">
                <div style="font-weight:600;font-size:0.92rem;color:var(--text);margin-bottom:4px;">{{ optional($order->hospital)->name ?? '—' }}</div>
                <div style="font-size:0.8rem;color:var(--text3);">{{ optional($order->hospital)->city ?? '' }}</div>
                <div style="font-size:0.8rem;color:var(--text3);">{{ optional($order->hospital)->contact_email ?? '' }}</div>
            </div>
        </div>
        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title"><i class="fa-solid fa-truck-medical" style="color:var(--lavender-dark);margin-right:8px;"></i>Fournisseur</span>
            </div>
            <div class="ms-card-body">
                <div style="font-weight:600;font-size:0.92rem;color:var(--text);margin-bottom:4px;">{{ optional($order->supplier)->name ?? '—' }}</div>
                <div style="font-size:0.8rem;color:var(--text3);">{{ optional($order->supplier)->email ?? '' }}</div>
                <div style="font-size:0.8rem;color:var(--text3);">{{ optional($order->supplier)->phone ?? '' }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="ms-card">
    <div class="ms-card-header">
        <span class="ms-card-title"><i class="fa-solid fa-sliders" style="color:var(--blue);margin-right:8px;"></i>Changer le statut</span>
    </div>
    <div class="ms-card-body">
        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" style="display:flex;gap:10px;align-items:center;">
            @csrf @method('PUT')
            <select name="status" class="ms-input ms-select" style="width:200px;">
                @foreach(['pending'=>'En attente','validated'=>'Validé','confirmed'=>'Confirmé','shipped'=>'Expédié','delivered'=>'Livré','cancelled'=>'Annulé'] as $val => $label)
                <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-ms-primary">
                <i class="fa-solid fa-floppy-disk"></i> Enregistrer
            </button>
        </form>
    </div>
</div>
@endsection