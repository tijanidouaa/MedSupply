@extends('layouts.hospital')
@section('title', 'Nouvelle Commande')
@section('page-title', 'Nouvelle Commande')
@section('page-subtitle')
MedSupply · Créer une commande
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Nouvelle commande</h2>
        <p>Sélectionnez un fournisseur et ajoutez vos produits</p>
    </div>
    <a href="{{ route('hospital.orders.index') }}" class="btn-ms-secondary">
        <i class="fa-solid fa-arrow-left"></i> Retour
    </a>
</div>

<form method="POST" action="{{ route('hospital.orders.store') }}" id="orderForm">
@csrf

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.4rem;align-items:start;">

    {{-- Colonne gauche --}}
    <div style="display:flex;flex-direction:column;gap:1.2rem;">

        {{-- Étape 1 : Fournisseur --}}
        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title">
                    <i class="fa-solid fa-truck-medical" style="color:var(--blue);margin-right:8px;"></i>
                    Étape 1 — Fournisseur
                </span>
            </div>
            <div class="ms-card-body">
                <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Choisir un fournisseur</label>
                <select name="supplier_id" id="supplierSelect" class="ms-input ms-select" required>
                    <option value="">Sélectionner un fournisseur...</option>
                    @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id')<div style="color:var(--peach-dark);font-size:0.74rem;margin-top:4px;">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Étape 2 : Produits --}}
        <div class="ms-card" id="productsSection">
            <div class="ms-card-header">
                <span class="ms-card-title">
                    <i class="fa-solid fa-box-open" style="color:var(--blue);margin-right:8px;"></i>
                    Étape 2 — Produits
                </span>
                <button type="button" id="addRowBtn"
                    style="background:var(--blue-soft);color:var(--blue-dark);border:none;border-radius:9px;padding:6px 14px;font-size:0.78rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;opacity:0.4;">
                    <i class="fa-solid fa-plus"></i> Ajouter un produit
                </button>
            </div>
            <div class="ms-card-body" style="padding:0;">
                <table class="ms-table" id="productsTable">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th style="text-align:center;width:90px;">Qté</th>
                            <th style="text-align:right;width:120px;">Prix unit.</th>
                            <th style="text-align:right;width:120px;">Sous-total</th>
                            <th style="width:40px;"></th>
                        </tr>
                    </thead>
                    <tbody id="productRows">
                        <tr id="emptyRow">
                            <td colspan="5" style="text-align:center;padding:2rem;color:var(--text3);">
                                <i class="fa-solid fa-box-open" style="display:block;font-size:24px;margin-bottom:8px;opacity:0.4;"></i>
                                Sélectionnez un fournisseur pour voir ses produits
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Notes --}}
        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title"><i class="fa-solid fa-note-sticky" style="color:var(--blue);margin-right:8px;"></i>Notes (optionnel)</span>
            </div>
            <div class="ms-card-body">
                <textarea name="notes" class="ms-input" rows="3" placeholder="Instructions spéciales, délai souhaité...">{{ old('notes') }}</textarea>
            </div>
        </div>

    </div>

    {{-- Colonne droite : récap --}}
    <div style="position:sticky;top:80px;display:flex;flex-direction:column;gap:1rem;">
        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title"><i class="fa-solid fa-receipt" style="color:var(--blue);margin-right:8px;"></i>Récapitulatif</span>
            </div>
            <div class="ms-card-body">
                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:1.2rem;">
                    <div style="display:flex;justify-content:space-between;font-size:0.84rem;">
                        <span style="color:var(--text3);">Fournisseur</span>
                        <span id="recapSupplier" style="font-weight:600;color:var(--text);">—</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.84rem;">
                        <span style="color:var(--text3);">Nb. produits</span>
                        <span id="recapCount" style="font-weight:600;">0</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.84rem;">
                        <span style="color:var(--text3);">Total HT</span>
                        <span id="recapHt" style="font-weight:600;">0.00 DH</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.84rem;">
                        <span style="color:var(--text3);">TVA (20%)</span>
                        <span id="recapTva" style="font-weight:600;">0.00 DH</span>
                    </div>
                    <div style="border-top:2px solid var(--border);padding-top:10px;display:flex;justify-content:space-between;">
                        <span style="font-weight:700;">Total TTC</span>
                        <span id="recapTtc" style="font-weight:700;font-size:1.1rem;color:var(--lavender-dark);">0.00 DH</span>
                    </div>
                </div>

                <button type="button" id="submitBtn"
                    class="btn-ms-primary" style="width:100%;justify-content:center;font-size:0.9rem;padding:12px;opacity:0.4;cursor:not-allowed;" disabled>
                    <i class="fa-solid fa-paper-plane"></i> Soumettre la commande
                </button>

                <div style="margin-top:10px;font-size:0.72rem;color:var(--text3);text-align:center;">
                    <i class="fa-solid fa-envelope" style="margin-right:4px;"></i>
                    Un email de confirmation vous sera envoyé
                </div>
            </div>
        </div>
    </div>

</div>
</form>

{{-- Modale confirmation --}}
<div id="modalConfirm" class="ms-modal-overlay" style="display:none;">
    <div class="ms-modal" onclick="event.stopPropagation()">
        <div class="ms-modal-icon" style="background:var(--mint);">
            <i class="fa-solid fa-paper-plane" style="color:var(--mint-dark);font-size:22px;"></i>
        </div>
        <h3 class="ms-modal-title">Confirmer la commande</h3>
        <p class="ms-modal-text">
            Fournisseur : <strong id="modalSupplier">—</strong><br>
            Total TTC : <strong id="modalTotal">—</strong><br><br>
            Un email de confirmation vous sera envoyé.
        </p>
        <div class="ms-modal-actions">
            <button type="button" id="cancelModalBtn" class="btn-ms-secondary">Annuler</button>
            <button type="button" id="confirmOrderBtn" class="btn-ms-primary">
                <i class="fa-solid fa-paper-plane"></i> Confirmer
            </button>
        </div>
    </div>
</div>

<style>
.ms-modal-overlay {
    position:fixed;inset:0;background:rgba(15,22,36,0.55);
    backdrop-filter:blur(4px);z-index:1000;
    align-items:center;justify-content:center;
    animation:fadeIn 0.2s ease;
}
.ms-modal {
    background:var(--card-bg);border-radius:20px;padding:2rem;
    width:100%;max-width:420px;box-shadow:0 24px 60px rgba(0,0,0,0.2);
    border:1px solid var(--border);text-align:center;
    animation:slideUp 0.25s cubic-bezier(0.22,1,0.36,1);
}
.ms-modal-icon { width:60px;height:60px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.2rem; }
.ms-modal-title { font-family:'Fraunces',serif;font-size:1.15rem;font-weight:600;color:var(--text);margin-bottom:8px; }
.ms-modal-text { font-size:0.85rem;color:var(--text2);line-height:1.6;margin-bottom:1.4rem; }
.ms-modal-actions { display:flex;gap:10px;justify-content:center; }
@keyframes fadeIn { from{opacity:0}to{opacity:1} }
@keyframes slideUp { from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)} }
</style>

@section('scripts')
<script>
const productsBySupplier = @json($productsBySupplier);

// ✅ Tous les event listeners via addEventListener — pas de onclick inline
document.addEventListener('DOMContentLoaded', function() {

    // Fournisseur change
    document.getElementById('supplierSelect').addEventListener('change', function() {
        loadProducts(this.value);
    });

    // Bouton ajouter ligne
    document.getElementById('addRowBtn').addEventListener('click', function() {
        addProductRow();
    });

    // Bouton soumettre → ouvre modal
    document.getElementById('submitBtn').addEventListener('click', function() {
        openModal('modalConfirm');
    });

    // Bouton annuler modal
    document.getElementById('cancelModalBtn').addEventListener('click', function() {
        closeModal('modalConfirm');
    });

    // Bouton confirmer commande
    document.getElementById('confirmOrderBtn').addEventListener('click', function() {
        document.getElementById('orderForm').submit();
    });

    // Fermer modal en cliquant sur overlay
    document.getElementById('modalConfirm').addEventListener('click', function(e) {
        if (e.target === this) closeModal('modalConfirm');
    });

    // Echap ferme modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal('modalConfirm');
    });
});

function loadProducts(supplierId) {
    const addBtn    = document.getElementById('addRowBtn');
    const tbody     = document.getElementById('productRows');
    const recapSupp = document.getElementById('recapSupplier');
    const supplierName = document.getElementById('supplierSelect').selectedOptions[0]?.text ?? '—';

    tbody.innerHTML = '';
    recapSupp.textContent = supplierId ? supplierName : '—';

    const key      = parseInt(supplierId);
    const products = productsBySupplier[key] || [];

    if (!supplierId || products.length === 0) {
        addBtn.style.opacity      = '0.4';
        addBtn.style.cursor       = 'not-allowed';
        addBtn.disabled           = true;
        tbody.innerHTML = `<tr id="emptyRow"><td colspan="5" style="text-align:center;padding:2rem;color:var(--text3);">
            <i class="fa-solid fa-box-open" style="display:block;font-size:24px;margin-bottom:8px;opacity:0.4;"></i>
            Aucun produit disponible pour ce fournisseur</td></tr>`;
        updateRecap();
        return;
    }

    // Activer le bouton ajouter
    addBtn.style.opacity  = '1';
    addBtn.style.cursor   = 'pointer';
    addBtn.disabled       = false;

    addProductRow();
}

function addProductRow() {
    const supplierId = document.getElementById('supplierSelect').value;
    const key        = parseInt(supplierId);
    const products   = productsBySupplier[key] || [];
    const tbody      = document.getElementById('productRows');
    const rowId      = 'row_' + Date.now();

    if (!supplierId || products.length === 0) return;

    const emptyRow = document.getElementById('emptyRow');
    if (emptyRow) emptyRow.remove();

    const options = products.map(p =>
        `<option value="${p.id}" data-price="${p.price}" data-stock="${p.stock}">${p.name} — stock: ${p.stock}</option>`
    ).join('');

    const tr = document.createElement('tr');
    tr.id = rowId;
    tr.innerHTML = `
        <td style="padding:8px 12px;">
            <select name="items[${rowId}][product_id]" class="ms-input ms-select"
                style="font-size:0.82rem;padding:7px 32px 7px 10px;" required>
                <option value="">Choisir un produit...</option>
                ${options}
            </select>
        </td>
        <td style="padding:8px 12px;text-align:center;">
            <input type="number" name="items[${rowId}][quantity]" value="1" min="1"
                class="ms-input" style="width:70px;padding:6px;text-align:center;font-size:0.82rem;" required>
        </td>
        <td style="padding:8px 12px;text-align:right;">
            <input type="number" name="items[${rowId}][unit_price]" value="0" min="0" step="0.01"
                id="price_${rowId}" class="ms-input"
                style="width:100px;padding:6px;text-align:right;font-size:0.82rem;" readonly>
        </td>
        <td style="padding:8px 12px;text-align:right;font-weight:700;color:var(--lavender-dark);"
            id="subtotal_${rowId}">0.00 DH</td>
        <td style="padding:8px 12px;text-align:center;">
            <button type="button" data-remove="${rowId}"
                class="icon-btn" style="background:var(--peach);color:var(--peach-dark);border-color:rgba(224,112,80,0.2);">
                <i class="fa-solid fa-trash" style="font-size:11px;"></i>
            </button>
        </td>
    `;

    // Event listeners sur les inputs de cette ligne
    tr.querySelector('select').addEventListener('change', function() {
        const opt   = this.selectedOptions[0];
        const price = opt?.dataset?.price ?? 0;
        const priceInput = document.getElementById('price_' + rowId);
        if (priceInput) priceInput.value = parseFloat(price).toFixed(2);
        updateRowTotal(rowId);
    });

    tr.querySelector('[name*="quantity"]').addEventListener('input', function() {
        updateRowTotal(rowId);
    });

    tr.querySelector('[name*="unit_price"]').addEventListener('input', function() {
        updateRowTotal(rowId);
    });

    tr.querySelector('[data-remove]').addEventListener('click', function() {
        removeRow(rowId);
    });

    tbody.appendChild(tr);
    updateRecap();
}

function updateRowTotal(rowId) {
    const row   = document.getElementById(rowId);
    if (!row) return;
    const qty      = parseFloat(row.querySelector('[name*="quantity"]')?.value) || 0;
    const price    = parseFloat(row.querySelector('[name*="unit_price"]')?.value) || 0;
    const subtotal = qty * price;
    const el = document.getElementById('subtotal_' + rowId);
    if (el) el.textContent = subtotal.toFixed(2) + ' DH';
    updateRecap();
}

function removeRow(rowId) {
    document.getElementById(rowId)?.remove();
    updateRecap();
    const tbody = document.getElementById('productRows');
    if (tbody.children.length === 0) {
        tbody.innerHTML = `<tr id="emptyRow"><td colspan="5" style="text-align:center;padding:2rem;color:var(--text3);">
            <i class="fa-solid fa-plus-circle" style="display:block;font-size:24px;margin-bottom:8px;opacity:0.4;"></i>
            Cliquez sur "Ajouter un produit"</td></tr>`;
    }
}

function updateRecap() {
    const rows  = document.querySelectorAll('#productRows tr[id^="row_"]');
    let totalHt = 0;
    let count   = 0;

    rows.forEach(row => {
        const qty   = parseFloat(row.querySelector('[name*="quantity"]')?.value) || 0;
        const price = parseFloat(row.querySelector('[name*="unit_price"]')?.value) || 0;
        const sel   = row.querySelector('select')?.value;
        if (sel) { totalHt += qty * price; count++; }
    });

    const tva = totalHt * 0.20;
    const ttc = totalHt + tva;

    document.getElementById('recapCount').textContent = count;
    document.getElementById('recapHt').textContent    = totalHt.toFixed(2) + ' DH';
    document.getElementById('recapTva').textContent   = tva.toFixed(2) + ' DH';
    document.getElementById('recapTtc').textContent   = ttc.toFixed(2) + ' DH';

    const submitBtn = document.getElementById('submitBtn');
    if (count > 0) {
        submitBtn.disabled      = false;
        submitBtn.style.opacity = '1';
        submitBtn.style.cursor  = 'pointer';
    } else {
        submitBtn.disabled      = true;
        submitBtn.style.opacity = '0.4';
        submitBtn.style.cursor  = 'not-allowed';
    }

    const supplierName = document.getElementById('supplierSelect').selectedOptions[0]?.text ?? '—';
    document.getElementById('modalSupplier').textContent = supplierName;
    document.getElementById('modalTotal').textContent    = ttc.toFixed(2) + ' DH';
}

function openModal(id) {
    const m = document.getElementById(id);
    m.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    const m = document.getElementById(id);
    m.style.display = 'none';
    document.body.style.overflow = '';
}
</script>
@endsection
@endsection