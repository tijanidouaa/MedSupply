@extends('layouts.hospital')
@section('title', 'Fournisseurs')
@section('page-title', 'Fournisseurs')
@section('page-subtitle', 'MedSupply · Liste des fournisseurs')

@section('content')
<div class="page-header">
    <div>
        <h2>Fournisseurs</h2>
        <p>{{ $suppliers->total() }} fournisseur(s) disponible(s)</p>
    </div>
</div>

<div class="ms-card">
    <div class="ms-card-header">
        <span class="ms-card-title"><i class="fa-solid fa-truck-medical" style="color:var(--blue);margin-right:6px;"></i>Liste des fournisseurs</span>
    </div>
    <table class="ms-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Statut</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
            <tr>
                <td style="font-weight:600;">{{ $supplier->name }}</td>
                <td style="color:var(--text3);">{{ $supplier->email ?? '—' }}</td>
                <td style="color:var(--text3);">{{ $supplier->phone ?? '—' }}</td>
                <td>
                    @if($supplier->verified ?? false)
                        <span class="ms-pill pill-active"><i class="fa-solid fa-circle-check"></i> Vérifié</span>
                    @else
                        <span class="ms-pill pill-pending"><i class="fa-solid fa-clock"></i> En attente</span>
                    @endif
                </td>
                <td>
                    @if($supplier->rating)
                        @for($i=1;$i<=5;$i++)
                            <i class="fa-solid fa-star" style="color:{{ $i <= $supplier->rating ? '#f59e0b' : 'var(--border)' }};font-size:12px;"></i>
                        @endfor
                    @else
                        <span style="color:var(--text3);">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:3rem;color:var(--text3);">
                    <i class="fa-solid fa-truck-medical" style="font-size:2rem;display:block;margin-bottom:8px;opacity:0.4;"></i>
                    Aucun fournisseur trouvé
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($suppliers->hasPages())
    <div style="padding:1rem 1.4rem;border-top:1px solid var(--border);">{{ $suppliers->links() }}</div>
    @endif
</div>
@endsection