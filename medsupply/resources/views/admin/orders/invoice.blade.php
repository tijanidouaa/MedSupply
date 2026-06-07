@extends('layouts.admin')
@section('title', 'Facture')
@section('page-title', 'Facture')
@section('page-subtitle')
{{ $order->reference }} · Facture
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Facture — {{ $order->reference }}</h2>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn-ms-primary">
            <i class="fa-solid fa-print"></i> Imprimer
        </button>
        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-ms-secondary">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="ms-card" id="invoice" style="max-width:800px;margin:0 auto;padding:2rem;">
    <!-- En-tête -->
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem;padding-bottom:1.5rem;border-bottom:2px solid var(--border);">
        <div>
            <div style="font-family:'Fraunces',serif;font-size:1.8rem;font-weight:600;color:var(--blue-dark);">MedSupply</div>
            <div style="font-size:0.8rem;color:var(--text3);margin-top:4px;">Plateforme de gestion médicale</div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:1.4rem;font-weight:700;color:var(--text);">FACTURE</div>
            <div style="font-family:monospace;font-size:0.9rem;color:var(--blue-dark);margin-top:4px;">{{ $order->reference }}</div>
            <div style="font-size:0.8rem;color:var(--text3);margin-top:4px;">Date : {{ $order->created_at->format('d/m/Y') }}</div>
        </div>
    </div>

    <!-- Parties -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem;">
        <div>
            <div style="font-size:0.72rem;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Facturé à</div>
            <div style="font-weight:700;font-size:0.92rem;color:var(--text);">{{ optional($order->hospital)->name ?? '—' }}</div>
            <div style="font-size:0.82rem;color:var(--text2);margin-top:4px;">{{ optional($order->hospital)->address ?? '' }}</div>
            <div style="font-size:0.82rem;color:var(--text2);">{{ optional($order->hospital)->city ?? '' }}</div>
            <div style="font-size:0.82rem;color:var(--text2);">{{ optional($order->hospital)->contact_email ?? '' }}</div>
        </div>
        <div>
            <div style="font-size:0.72rem;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Fournisseur</div>
            <div style="font-weight:700;font-size:0.92rem;color:var(--text);">{{ optional($order->supplier)->name ?? '—' }}</div>
            <div style="font-size:0.82rem;color:var(--text2);margin-top:4px;">{{ optional($order->supplier)->email ?? '' }}</div>
            <div style="font-size:0.82rem;color:var(--text2);">{{ optional($order->supplier)->phone ?? '' }}</div>
        </div>
    </div>

    <!-- Statut -->
    <div style="margin-bottom:2rem;">
        @php
            $statusMap = ['pending'=>['#fff3cd','#856404','En attente'],'validated'=>['var(--blue-soft)','var(--blue-dark)','Validé'],'confirmed'=>['var(--lavender)','var(--lavender-dark)','Confirmé'],'shipped'=>['#ede9fe','#6d28d9','Expédié'],'delivered'=>['var(--mint)','var(--mint-dark)','Livré'],'cancelled'=>['var(--peach)','var(--peach-dark)','Annulé']];
            $s = $statusMap[$order->status] ?? ['var(--bg)','var(--text)','Inconnu'];
        @endphp
        <span style="font-size:0.78rem;color:var(--text3);">Statut : </span>
        <span class="ms-pill" style="background:{{ $s[0] }};color:{{ $s[1] }};">{{ $s[2] }}</span>
    </div>

    <!-- Tableau montants -->
    <table style="width:100%;border-collapse:collapse;margin-bottom:2rem;">
        <thead>
            <tr style="background:var(--bg);">
                <th style="padding:10px 14px;text-align:left;font-size:0.78rem;color:var(--text3);text-transform:uppercase;border-bottom:1px solid var(--border);">Description</th>
                <th style="padding:10px 14px;text-align:right;font-size:0.78rem;color:var(--text3);text-transform:uppercase;border-bottom:1px solid var(--border);">Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding:12px 14px;font-size:0.86rem;color:var(--text);border-bottom:1px solid var(--border);">
                    Commande médicale — {{ $order->reference }}
                </td>
                <td style="padding:12px 14px;text-align:right;font-weight:600;border-bottom:1px solid var(--border);">
                    {{ number_format($order->total, 2) }} MAD
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Totaux -->
    <div style="display:flex;justify-content:flex-end;">
        <div style="width:280px;">
            <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:0.84rem;border-bottom:1px solid var(--border);">
                <span style="color:var(--text3);">Total HT</span>
                <span style="font-weight:600;">{{ number_format($order->total, 2) }} MAD</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:0.84rem;border-bottom:1px solid var(--border);">
                <span style="color:var(--text3);">TVA (20%)</span>
                <span style="font-weight:600;">{{ number_format($order->total * 0.2, 2) }} MAD</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:12px 0;font-size:1rem;font-weight:700;">
                <span>Total TTC</span>
                <span style="color:var(--lavender-dark);">{{ number_format($order->total * 1.2, 2) }} MAD</span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--border);text-align:center;font-size:0.76rem;color:var(--text3);">
        MedSupply · Plateforme de gestion des fournitures médicales · Maroc
    </div>
</div>

<style>
@media print {
    .topbar, .sidebar, .page-header .d-flex, .btn-ms-primary, .btn-ms-secondary { display: none !important; }
    .main { margin-left: 0 !important; }
    #invoice { box-shadow: none !important; border: none !important; }
}
</style>
@endsection