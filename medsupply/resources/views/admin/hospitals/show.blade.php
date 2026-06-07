@extends('layouts.admin')
@section('title', 'Détail Hôpital')
@section('page-title', 'Détail Hôpital')
@section('page-subtitle')
{{ $hospital->name }} · Fiche complète
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $hospital->name }}</h2>
        <p>{{ $hospital->city }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.hospitals.edit', $hospital->id) }}" class="btn-ms-primary">
            <i class="fa-solid fa-pen"></i> Modifier
        </a>
        <a href="{{ route('admin.hospitals.index') }}" class="btn-ms-secondary">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.4rem;">

    <!-- Informations générales -->
    <div class="ms-card">
        <div class="ms-card-header">
            <span class="ms-card-title"><i class="fa-solid fa-hospital" style="color:var(--blue);margin-right:8px;"></i>Informations générales</span>
        </div>
        <div class="ms-card-body">
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--border);">
                    <span style="font-size:0.8rem;color:var(--text3);font-weight:500;">Nom</span>
                    <span style="font-size:0.86rem;font-weight:600;color:var(--text);">{{ $hospital->name }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--border);">
                    <span style="font-size:0.8rem;color:var(--text3);font-weight:500;">Ville</span>
                    <span style="font-size:0.86rem;color:var(--text);">{{ $hospital->city }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--border);">
                    <span style="font-size:0.8rem;color:var(--text3);font-weight:500;">Adresse</span>
                    <span style="font-size:0.86rem;color:var(--text);text-align:right;max-width:200px;">{{ $hospital->address }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--border);">
                    <span style="font-size:0.8rem;color:var(--text3);font-weight:500;">Téléphone</span>
                    <span style="font-size:0.86rem;color:var(--text);">{{ $hospital->phone ?? '—' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:10px;border-bottom:1px solid var(--border);">
                    <span style="font-size:0.8rem;color:var(--text3);font-weight:500;">Email</span>
                    <span style="font-size:0.86rem;color:var(--blue-dark);">{{ $hospital->contact_email }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="font-size:0.8rem;color:var(--text3);font-weight:500;">Statut</span>
                    @if($hospital->is_active)
                        <span class="ms-pill" style="background:var(--mint);color:var(--mint-dark);">
                            <i class="fa-solid fa-circle" style="font-size:6px;"></i> Actif
                        </span>
                    @else
                        <span class="ms-pill" style="background:var(--peach);color:var(--peach-dark);">
                            <i class="fa-solid fa-circle" style="font-size:6px;"></i> Inactif
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div style="display:flex;flex-direction:column;gap:1rem;">
        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title"><i class="fa-solid fa-chart-bar" style="color:var(--blue);margin-right:8px;"></i>Statistiques</span>
            </div>
            <div class="ms-card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div style="background:var(--bg);border-radius:12px;padding:1rem;text-align:center;">
                        <div style="font-family:'Fraunces',serif;font-size:1.8rem;font-weight:600;color:var(--blue-dark);">
                            {{ $hospital->orders()->count() }}
                        </div>
                        <div style="font-size:0.74rem;color:var(--text3);margin-top:4px;">Commandes total</div>
                    </div>
                    <div style="background:var(--bg);border-radius:12px;padding:1rem;text-align:center;">
                        <div style="font-family:'Fraunces',serif;font-size:1.8rem;font-weight:600;color:var(--mint-dark);">
                            {{ $hospital->orders()->where('status','delivered')->count() }}
                        </div>
                        <div style="font-size:0.74rem;color:var(--text3);margin-top:4px;">Livrées</div>
                    </div>
                    <div style="background:var(--bg);border-radius:12px;padding:1rem;text-align:center;">
                        <div style="font-family:'Fraunces',serif;font-size:1.8rem;font-weight:600;color:var(--peach-dark);">
                            {{ $hospital->orders()->where('status','pending')->count() }}
                        </div>
                        <div style="font-size:0.74rem;color:var(--text3);margin-top:4px;">En attente</div>
                    </div>
                    <div style="background:var(--bg);border-radius:12px;padding:1rem;text-align:center;">
                        <div style="font-family:'Fraunces',serif;font-size:1.8rem;font-weight:600;color:var(--lavender-dark);">
                            {{ $hospital->stockItems()->count() }}
                        </div>
                        <div style="font-size:0.74rem;color:var(--text3);margin-top:4px;">Articles stock</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title"><i class="fa-solid fa-clock" style="color:var(--text3);margin-right:8px;"></i>Dates</span>
            </div>
            <div class="ms-card-body">
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;justify-content:space-between;">
                        <span style="font-size:0.8rem;color:var(--text3);">Créé le</span>
                        <span style="font-size:0.84rem;color:var(--text);">{{ $hospital->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="font-size:0.8rem;color:var(--text3);">Mis à jour</span>
                        <span style="font-size:0.84rem;color:var(--text);">{{ $hospital->updated_at->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dernières commandes -->
<div class="ms-card" style="margin-top:1.4rem;">
    <div class="ms-card-header">
        <span class="ms-card-title"><i class="fa-solid fa-file-invoice" style="color:var(--blue);margin-right:8px;"></i>Dernières commandes</span>
        <a href="{{ route('admin.orders.index') }}" class="btn-ms-secondary" style="padding:5px 12px;font-size:0.78rem;">Voir toutes</a>
    </div>
    <table class="ms-table">
        <thead>
            <tr><th>Référence</th><th>Fournisseur</th><th>Total</th><th>Statut</th><th>Date</th></tr>
        </thead>
        <tbody>
            @forelse($hospital->orders()->with('supplier')->latest()->take(5)->get() as $order)
            <tr>
                <td style="font-family:monospace;font-size:0.8rem;color:var(--blue-dark);">{{ $order->reference }}</td>
                <td>{{ optional($order->supplier)->name ?? '—' }}</td>
                <td><strong>{{ number_format($order->total, 2) }} MAD</strong></td>
                <td>
                    @php $pills = ['pending'=>['#fff3cd','#856404'],'validated'=>['var(--blue-soft)','var(--blue-dark)'],'confirmed'=>['var(--lavender)','var(--lavender-dark)'],'delivered'=>['var(--mint)','var(--mint-dark)'],'cancelled'=>['var(--peach)','var(--peach-dark)']]; $labels = ['pending'=>'En attente','validated'=>'Validé','confirmed'=>'Confirmé','delivered'=>'Livré','cancelled'=>'Annulé']; @endphp
                    <span class="ms-pill" style="background:{{ $pills[$order->status][0] ?? 'var(--bg)' }};color:{{ $pills[$order->status][1] ?? 'var(--text)' }};">
                        {{ $labels[$order->status] ?? $order->status }}
                    </span>
                </td>
                <td style="color:var(--text3);font-size:0.78rem;">{{ $order->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--text3);">Aucune commande</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Zone danger -->
<div class="ms-card" style="margin-top:1.4rem;border-color:rgba(224,112,80,0.3);">
    <div class="ms-card-header" style="border-bottom-color:rgba(224,112,80,0.2);">
        <span class="ms-card-title" style="color:var(--peach-dark);"><i class="fa-solid fa-triangle-exclamation" style="margin-right:8px;"></i>Zone dangereuse</span>
    </div>
    <div class="ms-card-body">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <div style="font-size:0.86rem;font-weight:600;color:var(--text);">Supprimer cet hôpital</div>
                <div style="font-size:0.78rem;color:var(--text3);margin-top:2px;">Cette action est irréversible. Toutes les données associées seront supprimées.</div>
            </div>
            <form action="{{ route('admin.hospitals.destroy', $hospital->id) }}" method="POST"
                  onsubmit="return confirm('Supprimer définitivement {{ $hospital->name }} ?')">
                @csrf @method('DELETE')
                <button type="submit" style="background:var(--peach);color:var(--peach-dark);border:1.5px solid rgba(224,112,80,0.3);border-radius:10px;padding:8px 16px;font-size:0.82rem;font-weight:600;cursor:pointer;">
                    <i class="fa-solid fa-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection