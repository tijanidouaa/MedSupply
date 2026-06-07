@extends('layouts.supplier')
@section('title', 'Mon Profil')
@section('page-title', 'Mon Profil')
@section('page-subtitle', 'MedSupply · Profil fournisseur')

@section('content')
<div style="max-width:700px;">
    <div class="ms-card">
        <div class="ms-card-header">
            <span class="ms-card-title"><i class="fa-solid fa-building" style="color:var(--lavender-dark);margin-right:6px;"></i>Profil fournisseur</span>
        </div>
        <div style="padding:1.4rem;">
            <div style="display:flex;align-items:center;gap:1.4rem;margin-bottom:1.4rem;padding-bottom:1.4rem;border-bottom:1px solid var(--border);">
                <div style="width:80px;height:80px;border-radius:18px;background:linear-gradient(135deg,var(--lavender-dark),var(--blue-dark));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:28px;flex-shrink:0;">
                    {{ strtoupper(substr($user->name ?? 'F', 0, 2)) }}
                </div>
                <div>
                    <div style="font-family:'Fraunces',serif;font-size:1.2rem;font-weight:600;color:var(--text);">{{ $user->name }}</div>
                    <div style="font-size:0.82rem;color:var(--text3);margin-top:4px;">{{ $user->email }}</div>
                    <div style="margin-top:8px;">
                        <span style="padding:3px 9px;border-radius:20px;font-size:0.7rem;font-weight:700;background:var(--lavender);color:var(--lavender-dark);">
                            <i class="fa-solid fa-truck-medical"></i> Fournisseur
                        </span>
                    </div>
                </div>
            </div>

            @foreach([['Email',$user->email,'fa-envelope'],['Rôle',$user->role ?? 'supplier','fa-id-badge'],['Membre depuis',$user->created_at->format('d/m/Y'),'fa-calendar'],['Statut email',$user->email_verified_at ? 'Vérifié' : 'Non vérifié','fa-circle-check']] as $row)
            <div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--border);">
                <div style="width:32px;height:32px;border-radius:9px;background:var(--lavender);color:var(--lavender-dark);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fa-solid {{ $row[2] }}" style="font-size:13px;"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-size:0.72rem;color:var(--text3);margin-bottom:2px;">{{ $row[0] }}</div>
                    <div style="font-size:0.84rem;font-weight:600;color:var(--text);">{{ $row[1] }}</div>
                </div>
            </div>
            @endforeach

            <div style="margin-top:1.4rem;">
                <a href="{{ route('supplier.settings.index') }}" class="btn-ms-primary" style="width:100%;justify-content:center;">
                    <i class="fa-solid fa-pen"></i> Modifier le profil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection