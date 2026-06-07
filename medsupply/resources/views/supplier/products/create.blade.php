@extends('layouts.supplier')
@section('title', 'Ajouter un produit')
@section('page-title', 'Ajouter un produit')
@section('page-subtitle')
MedSupply · Nouveau produit
@endsection

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.4rem;">
    <div>
        <h2 style="font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600;color:var(--text);">Nouveau produit</h2>
        <p style="font-size:0.8rem;color:var(--text3);">Remplissez les informations du produit</p>
    </div>
    <a href="{{ route('supplier.products.index') }}" class="btn-ms-secondary">
        <i class="fa-solid fa-arrow-left"></i> Retour
    </a>
</div>

<div style="max-width:600px;">
    <div class="ms-card">
        <div class="ms-card-header">
            <span class="ms-card-title"><i class="fa-solid fa-plus" style="color:var(--lavender-dark);margin-right:6px;"></i>Informations du produit</span>
        </div>
        <div style="padding:1.4rem;">
            {{-- enctype obligatoire pour l'upload d'image --}}
            <form method="POST" action="{{ route('supplier.products.store') }}" enctype="multipart/form-data">
                @csrf

                @if($errors->any())
                <div style="background:var(--peach);color:var(--peach-dark);padding:10px 14px;border-radius:10px;margin-bottom:1rem;font-size:0.82rem;">
                    <ul style="margin:0;padding-left:16px;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Nom -->
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Nom du produit *</label>
                    <input type="text" name="name" class="ms-input" value="{{ old('name') }}" required placeholder="Ex: Gants médicaux...">
                </div>

                <!-- Catégorie -->
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Catégorie *</label>
                    <select name="category" class="ms-input ms-select" required>
                        <option value="">-- Choisir une catégorie --</option>
                        <option value="consommables" {{ old('category') == 'consommables' ? 'selected' : '' }}>Consommables</option>
                        <option value="medicaments"  {{ old('category') == 'medicaments'  ? 'selected' : '' }}>Médicaments</option>
                        <option value="equipements"  {{ old('category') == 'equipements'  ? 'selected' : '' }}>Équipements</option>
                        <option value="autres"       {{ old('category') == 'autres'       ? 'selected' : '' }}>Autres</option>
                    </select>
                </div>

                <!-- Prix -->
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Prix unitaire (DH) *</label>
                    <input type="number" name="price" class="ms-input" value="{{ old('price') }}" required placeholder="0.00" step="0.01" min="0">
                </div>

                <!-- Stock initial + minimum -->
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Stock initial</label>
                        <input type="number" name="stock" class="ms-input" value="{{ old('stock', 0) }}" min="0" placeholder="0">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Stock minimum</label>
                        <input type="number" name="min_stock" class="ms-input" value="{{ old('min_stock', 10) }}" min="0" placeholder="10">
                    </div>
                </div>

                <!-- Image du produit -->
                <div style="margin-bottom:1.4rem;">
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">
                        <i class="fa-solid fa-image" style="color:var(--lavender-dark);margin-right:4px;"></i>Image du produit
                    </label>

                    <!-- Zone de prévisualisation -->
                    <div id="image-preview-wrapper" style="display:none;margin-bottom:10px;text-align:center;">
                        <img id="image-preview" src="#" alt="Aperçu"
                             style="max-height:160px;max-width:100%;border-radius:10px;border:2px solid var(--lavender);object-fit:contain;">
                    </div>

                    <!-- Zone de drop / bouton -->
                    <label for="image-input"
                           id="drop-zone"
                           style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;
                                  border:2px dashed var(--lavender);border-radius:12px;padding:24px 16px;
                                  cursor:pointer;background:var(--lavender-light, #f5f3ff);transition:border-color .2s;">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.6rem;color:var(--lavender-dark);"></i>
                        <span style="font-size:0.8rem;color:var(--text2);font-weight:600;">Cliquez ou déposez une image ici</span>
                        <span style="font-size:0.72rem;color:var(--text3);">PNG, JPG, WEBP — max 2 Mo</span>
                    </label>
                    <input type="file" id="image-input" name="image" accept="image/*"
                           style="display:none;" onchange="previewImage(event)">

                    @error('image')
                        <p style="color:var(--peach-dark);font-size:0.75rem;margin-top:4px;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-ms-primary" style="width:100%;justify-content:center;">
                    <i class="fa-solid fa-floppy-disk"></i> Enregistrer le produit
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    const wrapper = document.getElementById('image-preview-wrapper');
    const preview = document.getElementById('image-preview');
    const dropZone = document.getElementById('drop-zone');

    const reader = new FileReader();
    reader.onload = function(e) {
        preview.src = e.target.result;
        wrapper.style.display = 'block';
        dropZone.style.borderStyle = 'solid';
        dropZone.style.borderColor = 'var(--lavender-dark)';
    };
    reader.readAsDataURL(file);
}

// Drag & drop support
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('image-input');

dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    dropZone.style.borderColor = 'var(--blue)';
});
dropZone.addEventListener('dragleave', () => {
    dropZone.style.borderColor = 'var(--lavender)';
});
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
        previewImage({ target: fileInput });
    }
});
</script>
@endsection