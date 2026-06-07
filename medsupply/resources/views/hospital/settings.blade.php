@extends('layouts.hospital')
@section('title', 'Paramètres')
@section('page-title', 'Paramètres')
@section('page-subtitle', 'MedSupply · Configuration du compte')

@section('content')
<style>
.settings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.4rem; }
.settings-card { background: var(--card-bg); border-radius: var(--radius); box-shadow: var(--shadow); border: 1px solid var(--border); overflow: hidden; animation: fadeUp 0.5s ease both; }
.settings-card-header { padding: 1.1rem 1.4rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; }
.settings-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
.settings-card-title { font-size: 0.9rem; font-weight: 700; color: var(--text); }
.settings-card-body { padding: 1.4rem; }
.form-group { margin-bottom: 1rem; }
.form-label { display: block; font-size: 0.78rem; font-weight: 600; color: var(--text2); margin-bottom: 6px; }
.avatar-big { width: 68px; height: 68px; border-radius: 16px; background: linear-gradient(135deg, var(--mint-dark), var(--blue)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 24px; margin-bottom: 1.2rem; }
.info-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--border); font-size: 0.82rem; }
.info-row:last-child { border-bottom: none; }
.info-label { color: var(--text3); }
.info-value { color: var(--text); font-weight: 600; }
</style>

<div class="settings-grid">
    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-icon" style="background:var(--blue-soft);color:var(--blue-dark);">
                <i class="fa-solid fa-user"></i>
            </div>
            <span class="settings-card-title">Informations du profil</span>
        </div>
        <div class="settings-card-body">
            <div class="avatar-big">{{ strtoupper(substr($user->name ?? 'H', 0, 2)) }}</div>
            <form method="POST" action="{{ route('hospital.settings.update') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nom complet</label>
                    <input type="text" name="name" class="ms-input" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div style="color:var(--peach-dark);font-size:0.74rem;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Adresse email</label>
                    <input type="email" name="email" class="ms-input" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div style="color:var(--peach-dark);font-size:0.74rem;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn-ms-primary" style="width:100%;justify-content:center;">
                    <i class="fa-solid fa-floppy-disk"></i> Enregistrer
                </button>
            </form>
        </div>
    </div>

    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-icon" style="background:var(--lavender);color:var(--lavender-dark);">
                <i class="fa-solid fa-lock"></i>
            </div>
            <span class="settings-card-title">Changer le mot de passe</span>
        </div>
        <div class="settings-card-body">
            <form method="POST" action="{{ route('hospital.settings.update') }}">
                @csrf
                <input type="hidden" name="name" value="{{ $user->name }}">
                <input type="hidden" name="email" value="{{ $user->email }}">
                <div class="form-group">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password" class="ms-input" placeholder="Minimum 8 caractères">
                    @error('password')<div style="color:var(--peach-dark);font-size:0.74rem;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="ms-input" placeholder="Répétez le mot de passe">
                </div>
                <button type="submit" class="btn-ms-primary" style="width:100%;justify-content:center;background:linear-gradient(135deg,var(--lavender-dark),#6a5fc1);">
                    <i class="fa-solid fa-key"></i> Mettre à jour
                </button>
            </form>
        </div>
    </div>

    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-icon" style="background:var(--mint);color:var(--mint-dark);">
                <i class="fa-solid fa-hospital"></i>
            </div>
            <span class="settings-card-title">Informations du compte</span>
        </div>
        <div class="settings-card-body">
            <div class="info-row">
                <span class="info-label">Nom</span>
                <span class="info-value">{{ $user->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $user->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Rôle</span>
                <span class="info-value"><span class="ms-pill pill-chef">{{ $user->role ?? 'hospital_chief' }}</span></span>
            </div>
            <div class="info-row">
                <span class="info-label">Membre depuis</span>
                <span class="info-value">{{ $user->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email vérifié</span>
                <span class="info-value">
                    @if($user->email_verified_at)
                        <span style="color:var(--mint-dark);"><i class="fa-solid fa-circle-check"></i> Oui</span>
                    @else
                        <span style="color:var(--peach-dark);"><i class="fa-solid fa-circle-xmark"></i> Non</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-icon" style="background:var(--peach);color:var(--peach-dark);">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <span class="settings-card-title">Session</span>
        </div>
        <div class="settings-card-body">
            <div class="info-row">
                <span class="info-label">Connecté en tant que</span>
                <span class="info-value">{{ $user->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Dernière connexion</span>
                <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
            <div style="margin-top:1.4rem;">
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="btn-ms-secondary" style="width:100%;justify-content:center;border-color:var(--peach-dark);color:var(--peach-dark);">
                        <i class="fa-solid fa-right-from-bracket"></i> Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection