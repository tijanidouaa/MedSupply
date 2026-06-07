@extends('layouts.admin')
@section('title','Nouvel hôpital')
@section('page-title','Nouvel hôpital')
@section('page-subtitle','Ajouter un établissement à la plateforme')

@section('content')
<div class="page-header">
  <div><h2>Nouvel hôpital</h2></div>
  <a href="{{ route('admin.hospitals.index') }}" class="btn-ms-secondary">
    <i class="fa-solid fa-arrow-left"></i> Retour
  </a>
</div>

<div class="ms-card" style="max-width:700px">
  <div class="ms-card-header">
    <span class="ms-card-title"><i class="fa-solid fa-hospital" style="color:var(--blue);margin-right:8px"></i>Informations de l'établissement</span>
  </div>
  <div class="ms-card-body">
    <form action="{{ route('admin.hospitals.store') }}" method="POST">
      @csrf
      <div class="row g-3">
        <div class="col-md-12">
          <label style="font-size:0.8rem;font-weight:600;color:var(--text3);display:block;margin-bottom:5px;">Nom de l'établissement *</label>
          <input type="text" name="name" class="ms-input" placeholder="Ex: CHU Casablanca" required value="{{ old('name') }}">
        </div>
        <div class="col-md-6">
          <label style="font-size:0.8rem;font-weight:600;color:var(--text3);display:block;margin-bottom:5px;">Ville *</label>
          <input type="text" name="city" class="ms-input" placeholder="Ex: Casablanca" required value="{{ old('city') }}">
        </div>
        <div class="col-md-6">
          <label style="font-size:0.8rem;font-weight:600;color:var(--text3);display:block;margin-bottom:5px;">Téléphone</label>
          <input type="text" name="phone" class="ms-input" placeholder="Ex: 0522-000000" value="{{ old('phone') }}">
        </div>
        <div class="col-md-12">
          <label style="font-size:0.8rem;font-weight:600;color:var(--text3);display:block;margin-bottom:5px;">Adresse complète *</label>
          <input type="text" name="address" class="ms-input" placeholder="Ex: Bd Ibn Rochd, Casablanca" required value="{{ old('address') }}">
        </div>
        <div class="col-md-12">
          <label style="font-size:0.8rem;font-weight:600;color:var(--text3);display:block;margin-bottom:5px;">Email de contact *</label>
          <input type="email" name="contact_email" class="ms-input" placeholder="contact@hopital.ma" required value="{{ old('contact_email') }}">
        </div>
        <div class="col-md-12 d-flex justify-content-end gap-2 mt-2">
          <a href="{{ route('admin.hospitals.index') }}" class="btn-ms-secondary">Annuler</a>
          <button type="submit" class="btn-ms-primary"><i class="fa-solid fa-save"></i> Enregistrer</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection