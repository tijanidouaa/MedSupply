@extends('layouts.supplier')
@section('title', 'Commandes reçues')
@section('page-title', 'Commandes reçues')
@section('page-subtitle', 'MedSupply · Gestion des commandes')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.4rem;">
    <div>
        <h2 style="font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600;color:var(--text);">Commandes reçues</h2>
        <p style="font-size:0.8rem;color:var(--text3);">{{ $orders->total() }} commande(s) au total</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.4rem;">
    @foreach([['pending','En attente','fa-clock','peach'],['validated','Validée','fa-circle-check','blue'],['delivered','Livrée','fa-truck','mint'],['cancelled','Annulée','fa-xmark','lavender']] as $s)
    <div style="background:var(--card-bg);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--border);padding:1.2rem;animation:fadeUp 0.4s ease both;">
        <div style="width:38px;height:38px;border-radius:10px;background:var(--{{ $s[3] }});color:var(--{{ $s[3] }}-dark);display:flex;align-items:center;justify-content:center;margin-bottom:0.8rem;">
            <i class="fa-solid {{ $s[2] }}" style="font-size:15px;"></i>
        </div>
        <div style="font-size:0.74rem;color:var(--text3);text-transform:uppercase;letter-spacing:0.4px;margin-bottom:4px;">{{ $s[1] }}</div>
        <div style="font-family:'Fraunces',serif;font-size:1.6rem;font-weight:600;color:var(--text);">
            {{ $orders->where('status', $s[0])->count() }}
        </div>
    </div>
    @endforeach
</div>

<div class="ms-card">
    <div class="ms-card-header">
        <span class="ms-card-title"><i class="fa-solid fa-file-invoice" style="color:var(--lavender-dark);margin-right:6px;"></i>Liste des commandes</span>
    </div>
    <table class="ms-table">
        <thead>
            <tr>
                <th>Référence</th>
                <th>Hôpital</th>
                <th>Statut</th>
                <th>Total</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            @php
                $pills = ['pending'=>'background:#fff3cd;color:#856404','validated'=>'background:var(--blue-soft);color:var(--blue-dark)','delivered'=>'background:var(--mint);color:var(--mint-dark)','cancelled'=>'background:var(--peach);color:var(--peach-dark)'];
                $labels = ['pending'=>'En attente','validated'=>'Validée','delivered'=>'Livrée','cancelled'=>'Annulée'];
            @endphp
            <tr>
                <td style="font-weight:700;color:var(--lavender-dark);">{{ $order->reference }}</td>
                <td>{{ $order->hospital->name ?? '—' }}</td>
                <td>
                    <span style="padding:3px 9px;border-radius:20px;font-size:0.7rem;font-weight:700;{{ $pills[$order->status] ?? '' }}">
                        {{ $labels[$order->status] ?? $order->status }}
                    </span>
                </td>
                <td style="font-weight:600;">{{ number_format($order->total, 2) }} DH</td>
                <td style="color:var(--text3);font-size:0.8rem;">{{ $order->created_at->format('d/m/Y') }}</td>
                <td style="display:flex;gap:6px;">
                    <a href="{{ route('supplier.orders.show', $order->id) }}"
                       style="width:30px;height:30px;border-radius:8px;border:1.5px solid var(--border);background:var(--card-bg);display:inline-flex;align-items:center;justify-content:center;color:var(--text2);text-decoration:none;transition:all 0.2s;"
                       title="Voir">
                        <i class="fa-solid fa-eye" style="font-size:12px;"></i>
                    </a>
                    @if($order->status === 'pending')
                    <form method="POST" action="{{ route('supplier.orders.validate', $order->id) }}">
                        @csrf @method('PUT')
                        <button type="submit"
                            style="width:30px;height:30px;border-radius:8px;border:1.5px solid var(--mint-dark);background:var(--mint);color:var(--mint-dark);cursor:pointer;display:inline-flex;align-items:center;justify-content:center;"
                            title="Valider">
                            <i class="fa-solid fa-check" style="font-size:12px;"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:3rem;color:var(--text3);">
                    <i class="fa-solid fa-inbox" style="font-size:2rem;display:block;margin-bottom:8px;opacity:0.4;"></i>
                    Aucune commande reçue
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())
    <div style="padding:1rem 1.4rem;border-top:1px solid var(--border);">{{ $orders->links() }}</div>
    @endif
</div>
@endsection