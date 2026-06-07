@extends('layouts.supplier')
@section('title', 'Paramètres')
@section('page-title', 'Paramètres')
@section('page-subtitle', 'MedSupply · Configuration du compte')

@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.4rem;">

    <div class="ms-card">
        <div class="ms-card-header">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:10px;background:var(--lavender);color:var(--lavender-dark);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-user" style="font-size:14px;"></i>
                </div>
                <span class="ms-card-title">Informations du profil</span>
            </div>
        </div>
        <div style="padding:1.4rem;">
            <div style="width:68px;height:68px;border-radius:16px;background:linear-gradient(135deg,var(--lavender-dark),var(--blue-dark));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:24px;margin-bottom:1.2rem;">
                {{ strtoupper(substr($user->name ?? 'F', 0, 2)) }}
            </div>
            <form method="POST" action="{{ route('supplier.settings.update') }}">
                @csrf
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Nom complet</label>
                    <input type="text" name="name" class="ms-input" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div style="color:var(--peach-dark);font-size:0.74rem;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div style="margin-bottom:1.2rem;">
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Adresse email</label>
                    <input type="email" name="email" class="ms-input" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div style="color:var(--peach-dark);font-size:0.74rem;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn-ms-primary" style="width:100%;justify-content:center;">
                    <i class="fa-solid fa-floppy-disk"></i> Enregistrer
                </button>
            </form>
        </div>
    </div>

    <div class="ms-card">
        <div class="ms-card-header">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:10px;background:var(--lavender);color:var(--lavender-dark);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-lock" style="font-size:14px;"></i>
                </div>
                <span class="ms-card-title">Changer le mot de passe</span>
            </div>
        </div>
        <div style="padding:1.4rem;">
            <form method="POST" action="{{ route('supplier.settings.update') }}">
                @csrf
                <input type="hidden" name="name" value="{{ $user->name }}">
                <input type="hidden" name="email" value="{{ $user->email }}">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Nouveau mot de passe</label>
                    <input type="password" name="password" class="ms-input" placeholder="Minimum 8 caractères">
                    @error('password')<div style="color:var(--peach-dark);font-size:0.74rem;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div style="margin-bottom:1.2rem;">
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="ms-input" placeholder="Répétez le mot de passe">
                </div>
                <button type="submit" class="btn-ms-primary" style="width:100%;justify-content:center;background:linear-gradient(135deg,var(--lavender-dark),#6a5fc1);">
                    <i class="fa-solid fa-key"></i> Mettre à jour
                </button>
            </form>
        </div>
    </div>

    <div class="ms-card">
        <div class="ms-card-header">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:10px;background:var(--mint);color:var(--mint-dark);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-circle-info" style="font-size:14px;"></i>
                </div>
                <span class="ms-card-title">Informations du compte</span>
            </div>
        </div>
        <div style="padding:1.4rem;">
            @foreach([['Nom',$user->name],['Email',$user->email],['Rôle',$user->role ?? 'supplier'],['Membre depuis',$user->created_at->format('d/m/Y')]] as $row)
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);font-size:0.82rem;">
                <span style="color:var(--text3);">{{ $row[0] }}</span>
                <span style="font-weight:600;">{{ $row[1] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="ms-card">
        <div class="ms-card-header">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:10px;background:var(--peach);color:var(--peach-dark);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-shield-halved" style="font-size:14px;"></i>
                </div>
                <span class="ms-card-title">Session</span>
            </div>
        </div>
        <div style="padding:1.4rem;">
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);font-size:0.82rem;">
                <span style="color:var(--text3);">Connecté en tant que</span>
                <span style="font-weight:600;">{{ $user->name }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);font-size:0.82rem;">
                <span style="color:var(--text3);">Dernière connexion</span>
                <span style="font-weight:600;">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
            <div style="margin-top:1.2rem;">
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