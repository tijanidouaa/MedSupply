@extends('layouts.supplier')
@section('title', 'Importer des produits')
@section('page-title', 'Importer des produits')
@section('page-subtitle', 'MedSupply · Import CSV')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.4rem;">
    <div>
        <h2 style="font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600;color:var(--text);">Import CSV</h2>
        <p style="font-size:0.8rem;color:var(--text3);">Importez plusieurs produits en une seule fois</p>
    </div>
    <a href="{{ route('supplier.products.index') }}" class="btn-ms-secondary">
        <i class="fa-solid fa-arrow-left"></i> Retour
    </a>
</div>

@if(session('success'))
<div style="background:var(--mint);color:var(--mint-dark);padding:10px 14px;border-radius:10px;margin-bottom:1rem;font-size:0.82rem;">
    <i class="fa-solid fa-circle-check" style="margin-right:6px;"></i>{{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background:var(--peach);color:var(--peach-dark);padding:10px 14px;border-radius:10px;margin-bottom:1rem;font-size:0.82rem;">
    <i class="fa-solid fa-circle-xmark" style="margin-right:6px;"></i>{{ session('error') }}
</div>
@endif

<div style="max-width:640px;display:flex;flex-direction:column;gap:1.2rem;">

    {{-- Étape 1 : images --}}
    <div class="ms-card">
        <div class="ms-card-header">
            <span class="ms-card-title">
                <i class="fa-solid fa-images" style="color:var(--blue-dark,#3b6fd4);margin-right:6px;"></i>
                Étape 1 — Déposez vos images
            </span>
        </div>
        <div style="padding:1.2rem;font-size:0.82rem;color:var(--text2);line-height:1.7;">
            Copiez vos fichiers images dans ce dossier sur votre serveur :
            <div style="background:var(--bg,#f8f8fb);border:1px solid var(--border);border-radius:8px;padding:10px 14px;margin:10px 0;font-family:monospace;font-size:0.8rem;color:var(--text);">
                storage/app/public/products/
            </div>
            Exemple de noms de fichiers acceptés :
            <span style="font-family:monospace;background:var(--lavender-light,#f0eeff);padding:2px 6px;border-radius:4px;">gants.jpg</span>
            <span style="font-family:monospace;background:var(--lavender-light,#f0eeff);padding:2px 6px;border-radius:4px;">masque_ffp2.png</span>
        </div>
    </div>

    {{-- Étape 2 : CSV --}}
    <div class="ms-card">
        <div class="ms-card-header">
            <span class="ms-card-title">
                <i class="fa-solid fa-file-csv" style="color:var(--mint-dark,#1a7a5e);margin-right:6px;"></i>
                Étape 2 — Préparez votre CSV
            </span>
        </div>
        <div style="padding:1.2rem;font-size:0.82rem;color:var(--text2);line-height:1.7;">
            Le fichier CSV doit avoir ces colonnes (la colonne <code>image</code> = nom du fichier uniquement) :
            <div style="background:var(--bg,#f8f8fb);border:1px solid var(--border);border-radius:8px;padding:10px 14px;margin:10px 0;font-family:monospace;font-size:0.78rem;color:var(--text);overflow-x:auto;white-space:nowrap;">
                name,category,price,stock,min_stock,image<br>
                Gants chirurgicaux,consommables,45.00,500,50,gants.jpg<br>
                Paracétamol 500mg,medicaments,38.00,200,20,paracetamol.png<br>
                Tensiomètre,equipements,450.00,30,5,
            </div>
            Catégories valides : <code>consommables</code> · <code>medicaments</code> · <code>equipements</code> · <code>autres</code>
        </div>
    </div>

    {{-- Étape 3 : Upload --}}
    <div class="ms-card">
        <div class="ms-card-header">
            <span class="ms-card-title">
                <i class="fa-solid fa-upload" style="color:var(--lavender-dark);margin-right:6px;"></i>
                Étape 3 — Importez le fichier CSV
            </span>
        </div>
        <div style="padding:1.4rem;">
            <form method="POST" action="{{ route('supplier.products.import') }}" enctype="multipart/form-data">
                @csrf

                @if($errors->any())
                <div style="background:var(--peach);color:var(--peach-dark);padding:10px 14px;border-radius:10px;margin-bottom:1rem;font-size:0.82rem;">
                    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
                @endif

                <label for="csv-input"
                       id="csv-drop-zone"
                       style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;
                              border:2px dashed var(--mint-dark,#1a7a5e);border-radius:12px;padding:28px 16px;
                              cursor:pointer;background:var(--mint-light,#f0faf6);transition:border-color .2s;margin-bottom:1rem;">
                    <i class="fa-solid fa-file-arrow-up" style="font-size:1.8rem;color:var(--mint-dark,#1a7a5e);"></i>
                    <span id="csv-label" style="font-size:0.82rem;color:var(--text2);font-weight:600;">Cliquez ou déposez votre fichier CSV ici</span>
                    <span style="font-size:0.72rem;color:var(--text3);">Format .csv uniquement — max 5 Mo</span>
                </label>
                <input type="file" id="csv-input" name="csv_file" accept=".csv,text/csv"
                       style="display:none;" onchange="updateLabel(this)">

                <button type="submit" class="btn-ms-primary" style="width:100%;justify-content:center;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Lancer l'import
                </button>
            </form>
        </div>
    </div>

</div>

<script>
function updateLabel(input) {
    const label = document.getElementById('csv-label');
    const zone  = document.getElementById('csv-drop-zone');
    if (input.files.length) {
        label.textContent = input.files[0].name;
        zone.style.borderStyle = 'solid';
        zone.style.borderColor = 'var(--mint-dark, #1a7a5e)';
    }
}

const zone  = document.getElementById('csv-drop-zone');
const input = document.getElementById('csv-input');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor = 'var(--blue)'; });
zone.addEventListener('dragleave', () => { zone.style.borderColor = 'var(--mint-dark,#1a7a5e)'; });
zone.addEventListener('drop', e => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file) {
        const dt = new DataTransfer(); dt.items.add(file); input.files = dt.files;
        updateLabel(input);
    }
});
</script>
@endsection