@extends('layouts.hospital')
@section('title', 'Catalogue')
@section('page-title', 'Catalogue')
@section('page-subtitle', 'MedSupply · Produits disponibles')

@section('content')

@if(session('success'))
<div style="background:var(--mint);color:var(--mint-dark);padding:10px 16px;border-radius:11px;margin-bottom:1rem;font-size:0.84rem;font-weight:600;">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.4rem;">
    <div>
        <h2 style="font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600;color:var(--text);">Catalogue produits</h2>
        <p style="font-size:0.8rem;color:var(--text3);">{{ count($products) }} produit(s) disponible(s)</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;">
        {{-- Filtre catégorie --}}
        <select class="ms-input ms-select" style="width:180px;" onchange="filterCategory(this.value)">
            <option value="">Toutes catégories</option>
            <option value="consommables">Consommables</option>
            <option value="medicaments">Médicaments</option>
            <option value="equipements">Équipements</option>
            <option value="autres">Autres</option>
        </select>
        {{-- Recherche --}}
        <div class="search-box" style="width:200px;">
            <i class="fa-solid fa-magnifying-glass" style="color:var(--text3);font-size:12px;"></i>
            <input type="text" placeholder="Rechercher un produit..." oninput="searchProducts(this.value)">
        </div>
        {{-- Bouton panier --}}
        <a href="{{ route('hospital.cart.index') }}" class="btn-ms-primary" style="position:relative;">
            <i class="fa-solid fa-cart-shopping"></i> Mon Panier
            @if($cartCount > 0)
            <span style="position:absolute;top:-6px;right:-6px;background:#e05d5d;color:white;font-size:0.65rem;font-weight:700;padding:2px 6px;border-radius:20px;">{{ $cartCount }}</span>
            @endif
        </a>
    </div>
</div>

{{-- Comparaison prix --}}
@if(isset($comparisons) && $comparisons->count() > 0)
<div class="ms-card" style="margin-bottom:1.4rem;">
    <div class="ms-card-header">
        <span class="ms-card-title"><i class="fa-solid fa-scale-balanced" style="color:var(--lavender-dark);margin-right:8px;"></i>Comparaison des prix</span>
        <span style="font-size:0.75rem;color:var(--text3);">Même produit chez plusieurs fournisseurs</span>
    </div>
    <div class="ms-card-body" style="padding:0;">
        <table class="ms-table">
            <thead>
                <tr><th>Produit</th><th>Fournisseur</th><th style="text-align:right;">Prix</th><th style="text-align:center;">Stock</th><th></th></tr>
            </thead>
            <tbody>
                @foreach($comparisons as $comp)
                <tr>
                    <td style="font-weight:600;">{{ $comp->name }}</td>
                    <td style="color:var(--text3);font-size:0.82rem;">{{ $comp->supplier->name ?? '—' }}</td>
                    <td style="text-align:right;font-weight:700;color:{{ $comp->is_cheapest ? 'var(--mint-dark)' : 'var(--text)' }};">
                        {{ number_format($comp->price, 2) }} DH
                        @if($comp->is_cheapest)<span style="font-size:0.65rem;background:var(--mint);color:var(--mint-dark);padding:2px 6px;border-radius:10px;margin-left:4px;">Meilleur prix</span>@endif
                    </td>
                    <td style="text-align:center;">
                        <span class="ms-pill" style="background:{{ $comp->stock > 0 ? 'var(--mint)' : 'var(--peach)' }};color:{{ $comp->stock > 0 ? 'var(--mint-dark)' : 'var(--peach-dark)' }};">
                            {{ $comp->stock > 0 ? $comp->stock . ' unités' : 'Rupture' }}
                        </span>
                    </td>
                    <td>
                        @if($comp->stock > 0)
                        <form action="{{ route('hospital.cart.add') }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $comp->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn-ms-primary" style="font-size:0.75rem;padding:5px 10px;">
                                <i class="fa-solid fa-cart-plus"></i> Ajouter
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Grille produits --}}
<div id="productGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.2rem;">
    @forelse($products as $product)
    <div class="product-card"
         data-name="{{ strtolower($product->name) }}"
         data-category="{{ strtolower($product->category) }}"
         style="background:var(--card-bg);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--border);overflow:hidden;transition:transform .18s,box-shadow .18s;animation:fadeUp 0.4s ease both;"
         onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(91,155,213,0.14)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''">

        {{-- Image --}}
        <div style="width:100%;height:140px;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                @php
                    $icons = ['consommables'=>'fa-box','medicaments'=>'fa-pills','equipements'=>'fa-stethoscope','autres'=>'fa-cubes'];
                    $icon  = $icons[$product->category] ?? 'fa-pills';
                @endphp
                <div style="width:56px;height:56px;border-radius:14px;background:var(--lavender);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid {{ $icon }}" style="font-size:22px;color:var(--lavender-dark);"></i>
                </div>
            @endif
            {{-- Badge catégorie --}}
            <span style="position:absolute;top:8px;left:8px;background:rgba(255,255,255,0.92);color:var(--text2);font-size:0.65rem;font-weight:700;padding:3px 8px;border-radius:20px;text-transform:uppercase;letter-spacing:0.3px;">
                {{ ucfirst($product->category ?? 'autre') }}
            </span>
        </div>

        {{-- Infos --}}
        <div style="padding:1rem;">
            <p style="font-weight:700;font-size:0.9rem;color:var(--text);margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $product->name }}">
                {{ $product->name }}
            </p>
            <p style="font-size:0.73rem;color:var(--blue-dark);margin-bottom:10px;font-weight:600;">
                <i class="fa-solid fa-truck-medical" style="margin-right:3px;"></i>
                {{ $product->supplier->name ?? 'Fournisseur inconnu' }}
            </p>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <span style="font-weight:700;font-size:1rem;color:var(--lavender-dark);">
                    {{ number_format($product->price, 2) }} DH
                </span>
                @php $stock = $product->stock ?? 0; @endphp
                <span class="ms-pill" style="background:{{ $stock > 0 ? 'var(--mint)' : 'var(--peach)' }};color:{{ $stock > 0 ? 'var(--mint-dark)' : 'var(--peach-dark)' }};">
                    {{ $stock > 0 ? $stock . ' en stock' : 'Rupture' }}
                </span>
            </div>

            @if($stock > 0)
            {{-- Formulaire ajout panier avec quantité --}}
            <form action="{{ route('hospital.cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div style="display:flex;gap:6px;">
                    <input type="number" name="quantity" value="1" min="1" max="{{ $stock }}"
                           class="ms-input" style="width:60px;padding:7px;text-align:center;font-size:0.82rem;">
                    <button type="submit" class="btn-ms-primary" style="flex:1;justify-content:center;font-size:0.78rem;padding:7px 10px;">
                        <i class="fa-solid fa-cart-plus"></i> Ajouter
                    </button>
                </div>
            </form>
            @else
            <button disabled style="width:100%;background:var(--peach);color:var(--peach-dark);border:none;border-radius:11px;padding:8px;font-size:0.78rem;font-weight:600;cursor:not-allowed;">
                <i class="fa-solid fa-ban"></i> Rupture de stock
            </button>
            @endif
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--text3);">
        <i class="fa-solid fa-store" style="font-size:2.5rem;display:block;margin-bottom:10px;opacity:0.4;"></i>
        Aucun produit disponible
    </div>
    @endforelse
</div>

@endsection

@section('scripts')
<script>
function searchProducts(q) {
    q = q.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = card.dataset.name.includes(q) ? '' : 'none';
    });
}
function filterCategory(cat) {
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = (!cat || card.dataset.category === cat) ? '' : 'none';
    });
}
</script>
@endsection