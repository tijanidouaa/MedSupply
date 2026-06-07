@extends('layouts.admin')
@section('title', 'Modifier Hôpital')
@section('page-title', 'Modifier Hôpital')
@section('page-subtitle')
{{ $hospital->name }} · Modification
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Modifier — {{ $hospital->name }}</h2>
        <p>Mettez à jour les informations de l'hôpital</p>
    </div>
    <a href="{{ route('admin.hospitals.show', $hospital->id) }}" class="btn-ms-secondary">
        <i class="fa-solid fa-arrow-left"></i> Retour
    </a>
</div>

<div style="max-width:700px;">
    <div class="ms-card">
        <div class="ms-card-header">
            <span class="ms-card-title"><i class="fa-solid fa-pen" style="color:var(--blue);margin-right:6px;"></i>Informations de l'hôpital</span>
        </div>
        <div style="padding:1.4rem;">
            <form method="POST" action="{{ route('admin.hospitals.update', $hospital->id) }}">
                @csrf @method('PUT')

                @if($errors->any())
                <div style="background:var(--peach);color:var(--peach-dark);padding:10px 14px;border-radius:10px;margin-bottom:1rem;font-size:0.82rem;">
                    <ul style="margin:0;padding-left:16px;">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Nom de l'hôpital *</label>
                        <input type="text" name="name" class="ms-input" value="{{ old('name', $hospital->name) }}" required>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Ville *</label>
                        <input type="text" name="city" class="ms-input" value="{{ old('city', $hospital->city) }}" required>
                    </div>
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Adresse *</label>
                    <textarea name="address" class="ms-input" rows="2" required>{{ old('address', $hospital->address) }}</textarea>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Téléphone *</label>
                        <input type="text" name="phone" class="ms-input" value="{{ old('phone', $hospital->phone) }}" required>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Email de contact *</label>
                        <input type="email" name="contact_email" class="ms-input" value="{{ old('contact_email', $hospital->contact_email) }}" required>
                    </div>
                </div>

                <div style="margin-bottom:1.4rem;">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ $hospital->is_active ? 'checked' : '' }}
                            style="width:16px;height:16px;accent-color:var(--mint-dark);">
                        <span style="font-size:0.84rem;font-weight:500;color:var(--text2);">Hôpital actif</span>
                    </label>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn-ms-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Enregistrer
                    </button>
                    <a href="{{ route('admin.hospitals.show', $hospital->id) }}" class="btn-ms-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection