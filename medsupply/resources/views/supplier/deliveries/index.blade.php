@extends('layouts.supplier')
@section('title', 'Livraisons')
@section('page-title', 'Livraisons')
@section('page-subtitle', 'MedSupply · Historique des livraisons')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.4rem;">
    <div>
        <h2 style="font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600;color:var(--text);">Livraisons</h2>
        <p style="font-size:0.8rem;color:var(--text3);">{{ $deliveries->total() }} livraison(s)</p>
    </div>
</div>

<div class="ms-card">
    <div class="ms-card-header">
        <span class="ms-card-title"><i class="fa-solid fa-truck" style="color:var(--lavender-dark);margin-right:6px;"></i>Historique des livraisons</span>
    </div>
    <table class="ms-table">
        <thead>
            <tr>
                <th>Référence</th>
                <th>Hôpital</th>
                <th>Total</th>
                <th>Date de livraison</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deliveries as $delivery)
            <tr>
                <td style="font-weight:700;color:var(--lavender-dark);">{{ $delivery->reference }}</td>
                <td>{{ $delivery->hospital->name ?? '—' }}</td>
                <td style="font-weight:600;">{{ number_format($delivery->total, 2) }} DH</td>
                <td style="color:var(--text3);font-size:0.8rem;">{{ $delivery->updated_at->format('d/m/Y') }}</td>
                <td>
                    <span style="padding:3px 9px;border-radius:20px;font-size:0.7rem;font-weight:700;background:var(--mint);color:var(--mint-dark);">
                        <i class="fa-solid fa-circle-check"></i> Livré
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:3rem;color:var(--text3);">
                    <i class="fa-solid fa-truck" style="font-size:2rem;display:block;margin-bottom:8px;opacity:0.4;"></i>
                    Aucune livraison effectuée
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($deliveries->hasPages())
    <div style="padding:1rem 1.4rem;border-top:1px solid var(--border);">{{ $deliveries->links() }}</div>
    @endif
</div>
@endsection