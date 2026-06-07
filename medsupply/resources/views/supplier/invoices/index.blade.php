@extends('layouts.supplier')
@section('title', 'Factures')
@section('page-title', 'Factures')
@section('page-subtitle', 'MedSupply · Gestion des factures')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.4rem;">
    <div>
        <h2 style="font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600;color:var(--text);">Factures</h2>
        <p style="font-size:0.8rem;color:var(--text3);">{{ $invoices->total() }} facture(s)</p>
    </div>
</div>

<div class="ms-card">
    <div class="ms-card-header">
        <span class="ms-card-title"><i class="fa-solid fa-receipt" style="color:var(--lavender-dark);margin-right:6px;"></i>Liste des factures</span>
    </div>
    <table class="ms-table">
        <thead>
            <tr>
                <th>N° Facture</th>
                <th>Hôpital</th>
                <th>Montant</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
            <tr>
                <td style="font-weight:700;color:var(--lavender-dark);">FAC-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $invoice->hospital->name ?? '—' }}</td>
                <td style="font-weight:700;">{{ number_format($invoice->total, 2) }} DH</td>
                <td style="color:var(--text3);font-size:0.8rem;">{{ $invoice->updated_at->format('d/m/Y') }}</td>
                <td>
                    <span style="padding:3px 9px;border-radius:20px;font-size:0.7rem;font-weight:700;background:var(--mint);color:var(--mint-dark);">
                        <i class="fa-solid fa-circle-check"></i> Payée
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:3rem;color:var(--text3);">
                    <i class="fa-solid fa-receipt" style="font-size:2rem;display:block;margin-bottom:8px;opacity:0.4;"></i>
                    Aucune facture disponible
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($invoices->hasPages())
    <div style="padding:1rem 1.4rem;border-top:1px solid var(--border);">{{ $invoices->links() }}</div>
    @endif
</div>
@endsection