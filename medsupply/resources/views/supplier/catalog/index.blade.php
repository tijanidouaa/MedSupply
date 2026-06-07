@extends('layouts.supplier')
@section('title', 'Mon Catalogue')
@section('page-title', 'Mon Catalogue')
@section('page-subtitle', 'MedSupply · Catalogue public')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.4rem;">
    <div>
        <h2 style="font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600;color:var(--text);">Mon Catalogue</h2>
        <p style="font-size:0.8rem;color:var(--text3);">{{ count($products) }} produit(s) dans votre catalogue</p>
    </div>
    <a href="{{ route('supplier.products.create') }}" class="btn-ms-primary">
        <i class="fa-solid fa-plus"></i> Ajouter un produit
    </a>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.2rem;">
    @forelse($products as $product)
    <div style="background:var(--card-bg);border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--border);overflow:hidden;transition:transform 0.2s,box-shadow 0.2s;"
         onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 32px rgba(139,127,212,0.15)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''">

        {{-- Image ou placeholder --}}
        <div style="width:100%;height:150px;background:var(--lavender-light,#f0eeff);display:flex;align-items:center;justify-content:center;overflow:hidden;">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}"
                     alt="{{ $product->name }}"
                     style="width:100%;height:100%;object-fit:cover;">
            @else
                <div style="width:52px;height:52px;border-radius:14px;background:var(--lavender);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-pills" style="font-size:22px;color:var(--lavender-dark);"></i>
                </div>
            @endif
        </div>

        {{-- Infos --}}
        <div style="padding:1rem;">
            <div style="font-weight:700;font-size:0.92rem;color:var(--text);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ $product->name }}
            </div>
            <div style="font-size:0.75rem;color:var(--text3);margin-bottom:10px;">
                <i class="fa-solid fa-tag" style="margin-right:3px;"></i>{{ ucfirst($product->category ?? 'Produit médical') }}
            </div>
            <div style="font-size:1.05rem;font-weight:700;color:var(--lavender-dark);">
                {{ number_format($product->price ?? 0, 2) }} DH
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--text3);">
        <i class="fa-solid fa-store" style="font-size:2.5rem;display:block;margin-bottom:10px;opacity:0.4;"></i>
        Catalogue vide — ajoutez des produits
    </div>
    @endforelse
</div>
@endsection