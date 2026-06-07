@extends('layouts.admin')
@section('title', 'Nouvel utilisateur')
@section('page-title', 'Nouvel utilisateur')

@section('content')
<div class="ms-card">
  <div class="ms-card-header">
    <span class="ms-card-title"><i class="fa-solid fa-user-plus" style="color:var(--blue);margin-right:8px"></i>Créer un utilisateur</span>
    <a href="{{ route('admin.users.index') }}" class="btn-ms-secondary">
      <i class="fa-solid fa-arrow-left"></i> Retour
    </a>
  </div>
  <div class="ms-card-body">
    @if($errors->any())
    <div style="background:#fde8e8;border-radius:10px;padding:10px 14px;margin-bottom:1rem;color:#e05d5d;font-size:0.85rem;">
      <i class="fa-solid fa-circle-xmark"></i> {{ $errors->first() }}
    </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" style="max-width:600px">
      @csrf
      <div class="form-group mb-3">
        <label class="form-label">Nom complet</label>
        <input type="text" name="name" class="ms-input" value="{{ old('name') }}" placeholder="Nom complet" required>
      </div>
      <div class="form-group mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="ms-input" value="{{ old('email') }}" placeholder="email@exemple.com" required>
      </div>
      <div class="form-group mb-3">
        <label class="form-label">Rôle</label>
        <select name="role" class="ms-input ms-select" required>
          <option value="">Choisir un rôle</option>
          <option value="admin"          {{ old('role')=='admin'          ? 'selected' : '' }}>Administrateur</option>
          <option value="hospital_chief" {{ old('role')=='hospital_chief' ? 'selected' : '' }}>Chef hôpital</option>
          <option value="supplier"       {{ old('role')=='supplier'       ? 'selected' : '' }}>Fournisseur</option>
        </select>
      </div>
      <div class="form-group mb-3">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password" class="ms-input" placeholder="Minimum 8 caractères" required>
      </div>
      <div class="form-group mb-4">
        <label class="form-label">Confirmer le mot de passe</label>
        <input type="password" name="password_confirmation" class="ms-input" placeholder="Répéter le mot de passe" required>
      </div>
      <button type="submit" class="btn-ms-primary">
        <i class="fa-solid fa-check"></i> Créer l'utilisateur
      </button>
    </form>
  </div>
</div>
@endsection