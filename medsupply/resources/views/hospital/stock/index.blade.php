@extends('layouts.hospital')
@section('title', 'Mon Stock')
@section('page-title', 'Mon Stock')
@section('page-subtitle', 'MedSupply · Gestion du stock')

@section('content')

@if(session('success'))
<div style="background:var(--mint);color:var(--mint-dark);padding:10px 16px;border-radius:11px;margin-bottom:1rem;font-size:0.84rem;font-weight:600;">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

<div class="page-header">
    <div>
        <h2>Mon Stock</h2>
        <p>{{ $stocks->total() }} produit(s) en stock</p>
    </div>
    <button type="button" onclick="openModal('modalAdd')" class="btn-ms-primary">
        <i class="fa-solid fa-plus"></i> Ajouter un produit
    </button>
</div>

{{-- KPIs --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.4rem;">
    <div class="kpi-card">
        <div class="kpi-icon" style="background:var(--blue-soft);color:var(--blue-dark);"><i class="fa-solid fa-boxes-stacked"></i></div>
        <div class="kpi-label">Total produits</div>
        <div class="kpi-value">{{ $stocks->total() }}</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:var(--mint);color:var(--mint-dark);"><i class="fa-solid fa-circle-check"></i></div>
        <div class="kpi-label">En stock</div>
        <div class="kpi-value">{{ $okCount }}</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:var(--peach);color:var(--peach-dark);"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div class="kpi-label">Stock critique</div>
        <div class="kpi-value">{{ $criticalCount }}</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon" style="background:#f1f1f1;color:#555;"><i class="fa-solid fa-box-open"></i></div>
        <div class="kpi-label">Épuisés</div>
        <div class="kpi-value">{{ $outCount }}</div>
    </div>
</div>

{{-- Table --}}
<div class="ms-card">
    <div class="ms-card-header">
        <span class="ms-card-title"><i class="fa-solid fa-warehouse" style="color:var(--blue);margin-right:8px;"></i>Inventaire</span>
        <div class="search-box" style="width:200px;">
            <i class="fa-solid fa-magnifying-glass" style="color:var(--text3);font-size:12px;"></i>
            <input type="text" placeholder="Rechercher..." oninput="searchTable(this.value,'stockTable')">
        </div>
    </div>
    <div class="ms-card-body" style="padding:0;">
        <table class="ms-table" id="stockTable">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th style="text-align:center;">Quantité</th>
                    <th style="text-align:center;">Seuil min.</th>
                    <th style="text-align:right;">Prix unit.</th>
                    <th style="text-align:center;">Statut</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $item)
                <tr data-name="{{ strtolower($item->name . ' ' . $item->category) }}">
                    <td>
                        <div style="font-weight:600;font-size:0.85rem;">{{ $item->name }}</div>
                        @if($item->product)
                        <div style="font-size:0.72rem;color:var(--text3);">{{ $item->product->supplier->name ?? '' }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="ms-pill" style="background:var(--lavender);color:var(--lavender-dark);">
                            {{ ucfirst($item->category) }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <span style="font-weight:700;font-size:1rem;color:{{
                            $item->quantity === 0 ? '#555' :
                            ($item->quantity <= $item->min_quantity ? 'var(--peach-dark)' : 'var(--mint-dark)')
                        }};">{{ $item->quantity }}</span>
                    </td>
                    <td style="text-align:center;color:var(--text3);font-size:0.85rem;">{{ $item->min_quantity }}</td>
                    <td style="text-align:right;font-weight:600;">{{ number_format($item->price, 2) }} DH</td>
                    <td style="text-align:center;">
                        @if($item->quantity === 0)
                            <span class="ms-pill" style="background:#f1f1f1;color:#555;"><i class="fa-solid fa-ban" style="font-size:9px;"></i> Épuisé</span>
                        @elseif($item->quantity <= $item->min_quantity)
                            <span class="ms-pill" style="background:var(--peach);color:var(--peach-dark);"><i class="fa-solid fa-triangle-exclamation" style="font-size:9px;"></i> Critique</span>
                        @else
                            <span class="ms-pill" style="background:var(--mint);color:var(--mint-dark);"><i class="fa-solid fa-circle-check" style="font-size:9px;"></i> OK</span>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        <div style="display:flex;justify-content:flex-end;gap:6px;">
                            {{-- Modifier quantité --}}
                            <button type="button"
                                onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->quantity }}, {{ $item->min_quantity }})"
                                class="icon-btn" title="Modifier">
                                <i class="fa-solid fa-pen" style="font-size:11px;"></i>
                            </button>
                            {{-- Supprimer --}}
                            <button type="button"
                                onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->name) }}')"
                                class="icon-btn" style="background:var(--peach);color:var(--peach-dark);border-color:rgba(224,112,80,0.2);" title="Supprimer">
                                <i class="fa-solid fa-trash" style="font-size:11px;"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:3rem;color:var(--text3);">
                        <i class="fa-solid fa-warehouse" style="font-size:2rem;display:block;margin-bottom:8px;opacity:0.4;"></i>
                        Aucun produit en stock. Ajoutez votre stock initial.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($stocks->hasPages())
        <div style="padding:1rem 1.4rem;border-top:1px solid var(--border);">{{ $stocks->links() }}</div>
        @endif
    </div>
</div>

{{-- Modale Ajouter produit --}}
<div id="modalAdd" class="ms-modal-overlay" style="display:none;">
    <div class="ms-modal" style="max-width:500px;text-align:left;">
        <h3 class="ms-modal-title" style="text-align:center;margin-bottom:1.2rem;">
            <i class="fa-solid fa-plus" style="color:var(--blue);margin-right:8px;"></i>Ajouter un produit au stock
        </h3>
        <form method="POST" action="{{ route('hospital.stock.store') }}">
            @csrf
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:5px;">Nom du produit</label>
                    <input type="text" name="name" class="ms-input" placeholder="Ex: Seringues 5ml" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:5px;">Catégorie</label>
                        <select name="category" class="ms-input ms-select" required>
                            <option value="consommables">Consommables</option>
                            <option value="medicaments">Médicaments</option>
                            <option value="equipements">Équipements</option>
                            <option value="autres">Autres</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:5px;">Prix unitaire (DH)</label>
                        <input type="number" name="price" class="ms-input" placeholder="0.00" min="0" step="0.01" required>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:5px;">Quantité actuelle</label>
                        <input type="number" name="quantity" class="ms-input" placeholder="0" min="0" required>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:5px;">Seuil minimum</label>
                        <input type="number" name="min_quantity" class="ms-input" placeholder="10" min="1" value="10" required>
                    </div>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                    <button type="button" onclick="closeModal('modalAdd')" class="btn-ms-secondary">Annuler</button>
                    <button type="submit" class="btn-ms-primary"><i class="fa-solid fa-floppy-disk"></i> Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modale Modifier quantité --}}
<div id="modalEdit" class="ms-modal-overlay" style="display:none;">
    <div class="ms-modal" style="max-width:400px;text-align:left;">
        <h3 class="ms-modal-title" style="text-align:center;margin-bottom:1.2rem;">
            <i class="fa-solid fa-pen" style="color:var(--blue);margin-right:8px;"></i>Modifier le stock
        </h3>
        <p id="editProductName" style="text-align:center;font-weight:600;color:var(--text);margin-bottom:1.2rem;"></p>
        <form method="POST" id="editForm">
            @csrf @method('PUT')
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:5px;">Quantité actuelle</label>
                    <input type="number" name="quantity" id="editQuantity" class="ms-input" min="0" required>
                </div>
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:5px;">Seuil minimum</label>
                    <input type="number" name="min_quantity" id="editMinQuantity" class="ms-input" min="1" required>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                    <button type="button" onclick="closeModal('modalEdit')" class="btn-ms-secondary">Annuler</button>
                    <button type="submit" class="btn-ms-primary"><i class="fa-solid fa-floppy-disk"></i> Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modale Supprimer --}}
<div id="modalDelete" class="ms-modal-overlay" style="display:none;">
    <div class="ms-modal">
        <div class="ms-modal-icon" style="background:var(--peach);"><i class="fa-solid fa-trash" style="color:var(--peach-dark);font-size:22px;"></i></div>
        <h3 class="ms-modal-title">Supprimer ce produit ?</h3>
        <p class="ms-modal-text" id="deleteProductName"></p>
        <div class="ms-modal-actions">
            <button type="button" onclick="closeModal('modalDelete')" class="btn-ms-secondary">Annuler</button>
            <form id="deleteForm" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" style="background:var(--peach-dark);color:white;border:none;border-radius:11px;padding:9px 20px;font-size:0.84rem;font-weight:600;cursor:pointer;">
                    <i class="fa-solid fa-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.ms-modal-overlay { position:fixed;inset:0;background:rgba(15,22,36,0.55);backdrop-filter:blur(4px);z-index:1000;display:flex;align-items:center;justify-content:center;animation:fadeIn 0.2s ease; }
.ms-modal { background:var(--card-bg);border-radius:20px;padding:2rem;width:100%;max-width:420px;box-shadow:0 24px 60px rgba(0,0,0,0.2);border:1px solid var(--border);animation:slideUp 0.25s cubic-bezier(0.22,1,0.36,1); }
.ms-modal-icon { width:60px;height:60px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.2rem; }
.ms-modal-title { font-family:'Fraunces',serif;font-size:1.15rem;font-weight:600;color:var(--text);margin-bottom:8px; }
.ms-modal-text { font-size:0.85rem;color:var(--text2);line-height:1.6;margin-bottom:1.4rem; }
.ms-modal-actions { display:flex;gap:10px;justify-content:center; }
@keyframes fadeIn { from{opacity:0}to{opacity:1} }
@keyframes slideUp { from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)} }
</style>

@endsection

@section('scripts')
<script>
function openModal(id) { document.getElementById(id).style.display='flex'; document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).style.display='none'; document.body.style.overflow=''; }

function openEditModal(id, name, qty, minQty) {
    document.getElementById('editProductName').textContent = name;
    document.getElementById('editQuantity').value    = qty;
    document.getElementById('editMinQuantity').value = minQty;
    document.getElementById('editForm').action       = '/hospital/stock/' + id;
    openModal('modalEdit');
}

function openDeleteModal(id, name) {
    document.getElementById('deleteProductName').textContent = 'Voulez-vous supprimer "' + name + '" de votre stock ?';
    document.getElementById('deleteForm').action = '/hospital/stock/' + id;
    openModal('modalDelete');
}

function searchTable(q, id) {
    q = q.toLowerCase();
    document.querySelectorAll('#' + id + ' tbody tr').forEach(r => {
        r.style.display = r.dataset.name?.includes(q) ? '' : 'none';
    });
}

document.addEventListener('click', e => { if(e.target.classList.contains('ms-modal-overlay')){ e.target.style.display='none'; document.body.style.overflow=''; } });
document.addEventListener('keydown', e => { if(e.key==='Escape'){ document.querySelectorAll('.ms-modal-overlay').forEach(m=>m.style.display='none'); document.body.style.overflow=''; } });
</script>
@endsection