@extends('layouts.supplier')
@section('title', 'Mes Produits')
@section('page-title', 'Mes Produits')
@section('page-subtitle', 'MedSupply · Gestion des produits')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.4rem;">
    <div>
        <h2 style="font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600;color:var(--text);">Mes Produits</h2>
        <p style="font-size:0.8rem;color:var(--text3);">{{ $products->total() }} produit(s)</p>
    </div>
    <div style="display:flex;gap:0.6rem;">
        <a href="{{ route('supplier.products.import.form') }}" class="btn-ms-secondary">
            <i class="fa-solid fa-file-csv"></i> Importer CSV
        </a>
        <a href="{{ route('supplier.products.create') }}" class="btn-ms-primary">
            <i class="fa-solid fa-plus"></i> Ajouter un produit
        </a>
    </div>
</div>

@if(session('success'))
<div style="background:var(--mint);color:var(--mint-dark);padding:10px 14px;border-radius:10px;margin-bottom:1rem;font-size:0.82rem;">
    <i class="fa-solid fa-circle-check" style="margin-right:6px;"></i>{{ session('success') }}
</div>
@endif

@if($products->isEmpty())
<div class="ms-card" style="text-align:center;padding:3rem;">
    <i class="fa-solid fa-box" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.3;color:var(--lavender-dark);"></i>
    <p style="color:var(--text3);font-size:0.9rem;">Aucun produit ajouté</p>
    <a href="{{ route('supplier.products.create') }}" class="btn-ms-primary" style="margin-top:1rem;display:inline-flex;">
        <i class="fa-solid fa-plus"></i> Ajouter mon premier produit
    </a>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.2rem;">
    @foreach($products as $product)
    <div class="ms-card" style="padding:0;overflow:hidden;transition:transform .18s,box-shadow .18s;"
         onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.10)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''">

        {{-- Image ou placeholder --}}
        <div style="width:100%;height:150px;background:var(--lavender-light,#f0eeff);display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}"
                     alt="{{ $product->name }}"
                     style="width:100%;height:100%;object-fit:cover;">
            @else
                <i class="fa-solid fa-pills" style="font-size:2.5rem;color:var(--lavender-dark);opacity:0.5;"></i>
            @endif
        </div>

        {{-- Infos --}}
        <div style="padding:1rem;">
            <p style="font-weight:700;font-size:0.92rem;color:var(--text);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ $product->name }}
            </p>
            <p style="font-size:0.75rem;color:var(--text3);margin-bottom:10px;">
                <i class="fa-solid fa-tag" style="margin-right:3px;"></i>{{ ucfirst($product->category ?? '—') }}
            </p>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                <span style="font-weight:700;font-size:1rem;color:var(--lavender-dark);">
                    {{ number_format($product->price ?? 0, 2) }} DH
                </span>
                @php $stock = $product->stock ?? 0; $min = $product->min_stock ?? 10; @endphp
                <span class="ms-pill" style="background:{{ $stock <= $min ? 'var(--peach)' : 'var(--mint)' }};color:{{ $stock <= $min ? 'var(--peach-dark)' : 'var(--mint-dark)' }};">
                    {{ $stock }} en stock
                </span>
            </div>

            <p style="font-size:0.72rem;color:var(--text3);margin-bottom:12px;">
                <i class="fa-regular fa-calendar" style="margin-right:3px;"></i>{{ $product->created_at->format('d/m/Y') }}
            </p>

            {{-- Bouton supprimer --}}
            <form method="POST" action="{{ route('supplier.products.destroy', $product->id) }}"
                  onsubmit="return confirm('Supprimer ce produit ?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        style="width:100%;padding:7px;border:none;border-radius:8px;
                               background:var(--peach);color:var(--peach-dark);
                               font-size:0.78rem;font-weight:600;cursor:pointer;
                               display:flex;align-items:center;justify-content:center;gap:6px;
                               transition:opacity .15s;"
                        onmouseover="this.style.opacity='.8'"
                        onmouseout="this.style.opacity='1'">
                    <i class="fa-solid fa-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

@if($products->hasPages())
<div style="margin-top:1.4rem;">{{ $products->links() }}</div>
@endif
@endif
@endsection