@extends('layouts.hospital')
@section('title', 'Mon Panier')
@section('page-title', 'Mon Panier')
@section('page-subtitle', 'MedSupply · Récapitulatif avant commande')

@section('content')

@if(session('success'))
<div style="background:var(--mint);color:var(--mint-dark);padding:10px 16px;border-radius:11px;margin-bottom:1rem;font-size:0.84rem;font-weight:600;">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif
@if($errors->any())
<div style="background:var(--peach);color:var(--peach-dark);padding:10px 16px;border-radius:11px;margin-bottom:1rem;font-size:0.84rem;font-weight:600;">
    <i class="fa-solid fa-circle-xmark"></i> {{ $errors->first() }}
</div>
@endif

<div class="page-header">
    <div>
        <h2>Mon Panier</h2>
        <p>{{ $totalItems }} article(s) — {{ $groups->count() }} fournisseur(s)</p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('hospital.catalog.index') }}" class="btn-ms-secondary">
            <i class="fa-solid fa-arrow-left"></i> Continuer mes achats
        </a>
        @if($groups->count() > 0)
        <button type="button" onclick="openModal('modalVider')" class="btn-ms-secondary" style="color:var(--peach-dark);border-color:rgba(224,112,80,0.3);">
            <i class="fa-solid fa-trash"></i> Vider
        </button>
        @endif
    </div>
</div>

@if($groups->isEmpty())
<div style="text-align:center;padding:4rem 2rem;background:var(--card-bg);border-radius:var(--radius);border:1px solid var(--border);">
    <div style="width:80px;height:80px;border-radius:50%;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;margin:0 auto 1.2rem;">
        <i class="fa-solid fa-cart-shopping" style="font-size:32px;color:var(--blue-dark);"></i>
    </div>
    <h3 style="font-family:'Fraunces',serif;font-size:1.2rem;color:var(--text);margin-bottom:8px;">Votre panier est vide</h3>
    <p style="color:var(--text3);font-size:0.88rem;margin-bottom:1.4rem;">Ajoutez des produits depuis le catalogue pour passer une commande.</p>
    <a href="{{ route('hospital.catalog.index') }}" class="btn-ms-primary">
        <i class="fa-solid fa-store"></i> Voir le catalogue
    </a>
</div>

@else

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.4rem;align-items:start;">

    {{-- Colonne gauche --}}
    <div style="display:flex;flex-direction:column;gap:1.2rem;">
        @foreach($groups as $supplierId => $items)
        @php $supplier = $items->first()->supplier; @endphp
        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title">
                    <i class="fa-solid fa-truck-medical" style="color:var(--lavender-dark);margin-right:8px;"></i>
                    {{ $supplier->name ?? 'Fournisseur #' . $supplierId }}
                </span>
                <span style="font-size:0.75rem;color:var(--text3);">{{ $items->count() }} produit(s)</span>
            </div>
            <div class="ms-card-body" style="padding:0;">
                <table class="ms-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th style="text-align:center;">Qté</th>
                            <th style="text-align:center;">Remise</th>
                            <th style="text-align:right;">Prix unit.</th>
                            <th style="text-align:right;">Sous-total HT</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:0.85rem;color:var(--text);">{{ $item->product->name ?? '—' }}</div>
                                <div style="font-size:0.72rem;color:var(--text3);">{{ ucfirst($item->product->category ?? '') }}</div>
                            </td>
                            <td style="text-align:center;">
                                <form action="{{ route('hospital.cart.update', $item->id) }}" method="POST" style="display:flex;align-items:center;justify-content:center;">
                                    @csrf @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                           class="ms-input" style="width:60px;padding:5px;text-align:center;font-size:0.82rem;"
                                           onchange="this.form.submit()">
                                </form>
                            </td>
                            <td style="text-align:center;">
                                <form action="{{ route('hospital.cart.update', $item->id) }}" method="POST" style="display:flex;align-items:center;justify-content:center;">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="quantity" value="{{ $item->quantity }}">
                                    <div style="display:flex;align-items:center;gap:3px;">
                                        <input type="number" name="discount" value="{{ $item->discount }}" min="0" max="100" step="0.5"
                                               class="ms-input" style="width:60px;padding:5px;text-align:center;font-size:0.82rem;"
                                               onchange="this.form.submit()">
                                        <span style="font-size:0.75rem;color:var(--text3);">%</span>
                                    </div>
                                </form>
                            </td>
                            <td style="text-align:right;">
                                <div style="font-size:0.85rem;font-weight:600;">{{ number_format($item->unit_price, 2) }} DH</div>
                                @if($item->discount > 0)
                                <div style="font-size:0.72rem;color:var(--mint-dark);">→ {{ number_format($item->price_after_discount, 2) }} DH</div>
                                @endif
                            </td>
                            <td style="text-align:right;font-weight:700;color:var(--lavender-dark);">
                                {{ number_format($item->subtotal, 2) }} DH
                            </td>
                            <td>
                                <button type="button"
                                    onclick="openModal('modalSupprimer{{ $item->id }}')"
                                    class="icon-btn" style="background:var(--peach);color:var(--peach-dark);border-color:rgba(224,112,80,0.2);" title="Supprimer">
                                    <i class="fa-solid fa-trash" style="font-size:11px;"></i>
                                </button>
                                {{-- Formulaire suppression caché --}}
                                <form id="formSupprimer{{ $item->id }}" action="{{ route('hospital.cart.remove', $item->id) }}" method="POST" style="display:none;">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:var(--bg);">
                            <td colspan="4" style="text-align:right;font-size:0.82rem;color:var(--text3);padding:10px 12px;">Sous-total fournisseur HT</td>
                            <td style="text-align:right;font-weight:700;padding:10px 12px;">{{ number_format($items->sum('subtotal'), 2) }} DH</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Modale suppression article --}}
        @foreach($items as $item)
        <div id="modalSupprimer{{ $item->id }}" class="ms-modal-overlay" style="display:none;">
            <div class="ms-modal">
                <div class="ms-modal-icon" style="background:var(--peach);">
                    <i class="fa-solid fa-trash" style="color:var(--peach-dark);font-size:22px;"></i>
                </div>
                <h3 class="ms-modal-title">Retirer l'article ?</h3>
                <p class="ms-modal-text">Voulez-vous retirer <strong>{{ $item->product->name ?? 'cet article' }}</strong> de votre panier ?</p>
                <div class="ms-modal-actions">
                    <button type="button" onclick="closeModal('modalSupprimer{{ $item->id }}')" class="btn-ms-secondary">Annuler</button>
                    <button type="button" onclick="document.getElementById('formSupprimer{{ $item->id }}').submit()"
                        style="background:var(--peach-dark);color:white;border:none;border-radius:11px;padding:9px 20px;font-size:0.84rem;font-weight:600;cursor:pointer;">
                        <i class="fa-solid fa-trash"></i> Retirer
                    </button>
                </div>
            </div>
        </div>
        @endforeach

        @endforeach
    </div>

    {{-- Colonne droite --}}
    <div style="position:sticky;top:80px;display:flex;flex-direction:column;gap:1rem;">
        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title"><i class="fa-solid fa-receipt" style="color:var(--blue);margin-right:8px;"></i>Récapitulatif</span>
            </div>
            <div class="ms-card-body">
                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:1.2rem;">
                    <div style="display:flex;justify-content:space-between;font-size:0.84rem;">
                        <span style="color:var(--text3);">Total HT</span>
                        <span style="font-weight:600;">{{ number_format($totalHt, 2) }} DH</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.84rem;">
                        <span style="color:var(--text3);">TVA (20%)</span>
                        <span style="font-weight:600;">{{ number_format($totalTtc - $totalHt, 2) }} DH</span>
                    </div>
                    <div style="border-top:2px solid var(--border);padding-top:10px;display:flex;justify-content:space-between;">
                        <span style="font-weight:700;font-size:0.95rem;">Total TTC</span>
                        <span style="font-weight:700;font-size:1.1rem;color:var(--lavender-dark);">{{ number_format($totalTtc, 2) }} DH</span>
                    </div>
                </div>

                @if($groups->count() > 1)
                <div style="background:var(--blue-soft);border-radius:10px;padding:10px 14px;margin-bottom:1rem;font-size:0.78rem;color:var(--blue-dark);">
                    <i class="fa-solid fa-circle-info" style="margin-right:6px;"></i>
                    {{ $groups->count() }} commandes seront créées (une par fournisseur)
                </div>
                @endif

                {{-- Bouton checkout → ouvre modale --}}
                <button type="button" onclick="openModal('modalCheckout')" class="btn-ms-primary" style="width:100%;justify-content:center;font-size:0.9rem;padding:12px;">
                    <i class="fa-solid fa-paper-plane"></i> Passer la commande
                </button>
                {{-- Formulaire checkout caché --}}
                <form id="formCheckout" action="{{ route('hospital.cart.checkout') }}" method="POST" style="display:none;">
                    @csrf
                </form>

                <div style="margin-top:10px;font-size:0.72rem;color:var(--text3);text-align:center;">
                    <i class="fa-solid fa-envelope" style="margin-right:4px;"></i>
                    Un email de confirmation vous sera envoyé
                </div>
            </div>
        </div>

        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title" style="font-size:0.82rem;">Fournisseurs concernés</span>
            </div>
            <div class="ms-card-body">
                @foreach($groups as $supplierId => $items)
                @php $supplier = $items->first()->supplier; @endphp
                <div style="display:flex;align-items:center;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:28px;height:28px;border-radius:8px;background:var(--lavender);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:var(--lavender-dark);">
                            {{ strtoupper(substr($supplier->name ?? 'F', 0, 2)) }}
                        </div>
                        <span style="font-size:0.82rem;font-weight:600;color:var(--text);">{{ $supplier->name ?? '—' }}</span>
                    </div>
                    <span style="font-size:0.75rem;font-weight:700;color:var(--lavender-dark);">{{ number_format($items->sum('subtotal'), 2) }} DH</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Modale checkout --}}
<div id="modalCheckout" class="ms-modal-overlay" style="display:none;">
    <div class="ms-modal">
        <div class="ms-modal-icon" style="background:var(--mint);">
            <i class="fa-solid fa-paper-plane" style="color:var(--mint-dark);font-size:22px;"></i>
        </div>
        <h3 class="ms-modal-title">Confirmer la commande</h3>
        <p class="ms-modal-text">
            Vous allez passer <strong>{{ $groups->count() }} commande(s)</strong> pour un total de
            <strong>{{ number_format($totalTtc, 2) }} DH TTC</strong>.<br>
            Un email de confirmation vous sera envoyé.
        </p>
        <div class="ms-modal-actions">
            <button type="button" onclick="closeModal('modalCheckout')" class="btn-ms-secondary">Annuler</button>
            <button type="button" onclick="document.getElementById('formCheckout').submit()" class="btn-ms-primary">
                <i class="fa-solid fa-paper-plane"></i> Confirmer
            </button>
        </div>
    </div>
</div>

{{-- Modale vider panier --}}
<div id="modalVider" class="ms-modal-overlay" style="display:none;">
    <div class="ms-modal">
        <div class="ms-modal-icon" style="background:var(--peach);">
            <i class="fa-solid fa-trash" style="color:var(--peach-dark);font-size:22px;"></i>
        </div>
        <h3 class="ms-modal-title">Vider le panier ?</h3>
        <p class="ms-modal-text">Tous les articles seront supprimés. Cette action est irréversible.</p>
        <div class="ms-modal-actions">
            <button type="button" onclick="closeModal('modalVider')" class="btn-ms-secondary">Annuler</button>
            <button type="button" onclick="document.getElementById('formVider').submit()"
                style="background:var(--peach-dark);color:white;border:none;border-radius:11px;padding:9px 20px;font-size:0.84rem;font-weight:600;cursor:pointer;">
                <i class="fa-solid fa-trash"></i> Vider
            </button>
        </div>
    </div>
</div>
<form id="formVider" action="{{ route('hospital.cart.clear') }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>

@endif

<style>
.ms-modal-overlay {
    position: fixed; inset: 0; background: rgba(15,22,36,0.55);
    backdrop-filter: blur(4px); z-index: 1000;
    display: flex; align-items: center; justify-content: center;
    animation: fadeIn 0.2s ease;
}
.ms-modal {
    background: var(--card-bg); border-radius: 20px; padding: 2rem;
    width: 100%; max-width: 420px; box-shadow: 0 24px 60px rgba(0,0,0,0.2);
    border: 1px solid var(--border); text-align: center;
    animation: slideUp 0.25s cubic-bezier(0.22,1,0.36,1);
}
.ms-modal-icon {
    width: 60px; height: 60px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.2rem;
}
.ms-modal-title {
    font-family: 'Fraunces', serif; font-size: 1.15rem;
    font-weight: 600; color: var(--text); margin-bottom: 8px;
}
.ms-modal-text {
    font-size: 0.85rem; color: var(--text2); line-height: 1.6; margin-bottom: 1.4rem;
}
.ms-modal-actions {
    display: flex; gap: 10px; justify-content: center;
}
@keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
@keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
</style>

@endsection

@section('scripts')
<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
    document.body.style.overflow = '';
}
// Fermer en cliquant sur l'overlay
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('ms-modal-overlay')) {
        e.target.style.display = 'none';
        document.body.style.overflow = '';
    }
});
// Fermer avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.ms-modal-overlay').forEach(m => {
            m.style.display = 'none';
        });
        document.body.style.overflow = '';
    }
});
</script>
@endsection