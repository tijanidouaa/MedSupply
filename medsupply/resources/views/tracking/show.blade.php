@extends('layouts.hospital')
@section('title', 'Suivi Commande')
@section('page-title', 'Suivi de commande')
@section('page-subtitle', 'MedSupply · {{ $order->reference }}')

@section('content')
<div class="page-header">
    <div>
        <h2>Suivi — {{ $order->reference }}</h2>
        <p>Tracking en temps réel via 17Track</p>
    </div>
    <a href="{{ route('hospital.orders.show', $order->id) }}" class="btn-ms-secondary">
        <i class="fa-solid fa-arrow-left"></i> Retour à la commande
    </a>
</div>

{{-- Infos commande --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.4rem;margin-bottom:1.4rem;">

    {{-- Statut tracking 17Track --}}
    <div class="ms-card">
        <div class="ms-card-header">
            <span class="ms-card-title"><i class="fa-solid fa-satellite-dish" style="color:var(--blue);margin-right:8px"></i>Statut du colis</span>
            @if($order->tracking_number)
            <span style="font-size:0.75rem;color:var(--text3);font-family:monospace;">{{ $order->tracking_number }}</span>
            @endif
        </div>
        <div class="ms-card-body">

            @if(!$order->tracking_number)
                {{-- Pas encore de tracking --}}
                <div style="text-align:center;padding:2rem 0;">
                    <div style="width:64px;height:64px;border-radius:50%;background:var(--lavender);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <i class="fa-solid fa-barcode" style="font-size:24px;color:var(--lavender-dark);"></i>
                    </div>
                    <p style="color:var(--text3);font-size:0.88rem;margin-bottom:1.2rem;">Aucun numéro de tracking assigné à cette commande.</p>
                    <button onclick="document.getElementById('trackingForm').style.display='block';this.style.display='none'" class="btn-ms-primary" style="font-size:0.82rem;">
                        <i class="fa-solid fa-plus"></i> Ajouter un numéro de tracking
                    </button>
                </div>

            @elseif($error)
                {{-- Erreur API --}}
                <div style="background:var(--peach);color:var(--peach-dark);padding:14px 18px;border-radius:11px;font-size:0.84rem;display:flex;align-items:center;gap:10px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    {{ $error }}
                </div>

            @elseif($tracking)
                {{-- Statut principal --}}
                @php
                    $statusMap = [
                        0  => ['label' => 'En attente',      'color' => 'var(--peach-dark)',    'bg' => 'var(--peach)',    'icon' => 'fa-clock'],
                        10 => ['label' => 'En transit',      'color' => 'var(--blue-dark)',     'bg' => 'var(--blue-soft)','icon' => 'fa-truck'],
                        20 => ['label' => 'Avis de passage', 'color' => 'var(--lavender-dark)', 'bg' => 'var(--lavender)', 'icon' => 'fa-bell'],
                        30 => ['label' => 'Livré',           'color' => 'var(--mint-dark)',     'bg' => 'var(--mint)',     'icon' => 'fa-box-open'],
                        35 => ['label' => 'Retourné',        'color' => 'var(--peach-dark)',    'bg' => 'var(--peach)',    'icon' => 'fa-rotate-left'],
                        40 => ['label' => 'Problème',        'color' => '#856404',              'bg' => '#fff3cd',         'icon' => 'fa-triangle-exclamation'],
                    ];
                    $stateCode = $tracking['e'] ?? 0;
                    $state     = $statusMap[$stateCode] ?? $statusMap[0];
                @endphp

                <div style="display:flex;align-items:center;gap:16px;padding:1rem;background:{{ $state['bg'] }};border-radius:12px;margin-bottom:1.4rem;">
                    <div style="width:52px;height:52px;border-radius:50%;background:white;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                        <i class="fa-solid {{ $state['icon'] }}" style="font-size:20px;color:{{ $state['color'] }};"></i>
                    </div>
                    <div>
                        <div style="font-size:1rem;font-weight:700;color:{{ $state['color'] }};">{{ $state['label'] }}</div>
                        @if(!empty($tracking['z1'][0]['a']))
                        <div style="font-size:0.8rem;color:var(--text2);margin-top:2px;">{{ $tracking['z1'][0]['a'] ?? '' }}</div>
                        @endif
                    </div>
                    @if(!empty($tracking['c']))
                    <div style="margin-left:auto;text-align:right;">
                        <div style="font-size:0.7rem;color:var(--text3);">Transporteur</div>
                        <div style="font-size:0.85rem;font-weight:600;color:var(--text);">{{ $tracking['c'] }}</div>
                    </div>
                    @endif
                </div>

                {{-- Checkpoints --}}
                @if(count($checkpoints) > 0)
                <div style="font-size:0.75rem;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px;">
                    Historique des événements
                </div>
                <div style="position:relative;">
                    {{-- Ligne verticale --}}
                    <div style="position:absolute;left:15px;top:0;bottom:0;width:2px;background:var(--border);z-index:0;"></div>

                    @foreach($checkpoints as $i => $cp)
                    <div style="display:flex;gap:16px;align-items:flex-start;margin-bottom:16px;position:relative;z-index:1;">
                        <div style="width:32px;height:32px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:11px;
                            background:{{ $i === 0 ? 'var(--blue)' : 'var(--card-bg)' }};
                            color:{{ $i === 0 ? 'white' : 'var(--text3)' }};
                            border:2px solid {{ $i === 0 ? 'var(--blue)' : 'var(--border)' }};">
                            <i class="fa-solid {{ $i === 0 ? 'fa-location-dot' : 'fa-circle' }}" style="font-size:{{ $i === 0 ? '12px' : '6px' }}"></i>
                        </div>
                        <div style="flex:1;padding-bottom:4px;">
                            <div style="font-size:0.82rem;font-weight:{{ $i === 0 ? '700' : '500' }};color:{{ $i === 0 ? 'var(--text)' : 'var(--text2)' }};">
                                {{ $cp['z'] ?? $cp['a'] ?? '—' }}
                            </div>
                            @if(!empty($cp['a']))
                            <div style="font-size:0.76rem;color:var(--text3);margin-top:2px;">{{ $cp['a'] }}</div>
                            @endif
                            <div style="font-size:0.72rem;color:var(--text3);margin-top:3px;">
                                <i class="fa-regular fa-clock" style="margin-right:4px;"></i>
                                {{ $cp['a'] ?? $cp['b'] ?? '—' }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="text-align:center;padding:1.5rem;color:var(--text3);font-size:0.84rem;">
                    <i class="fa-solid fa-hourglass-half" style="display:block;font-size:24px;margin-bottom:8px;opacity:0.4;"></i>
                    Aucun événement de transport disponible pour l'instant.
                </div>
                @endif
            @endif

            {{-- Formulaire ajout tracking (caché par défaut si tracking_number existe) --}}
            <div id="trackingForm" style="{{ $order->tracking_number ? 'display:none' : 'display:none' }}">
                <hr style="border-color:var(--border);margin:1.2rem 0;">
                <div style="font-size:0.85rem;font-weight:600;color:var(--text);margin-bottom:10px;">
                    <i class="fa-solid fa-barcode" style="margin-right:6px;color:var(--blue);"></i>
                    Modifier le numéro de tracking
                </div>
                <form action="{{ route('hospital.orders.tracking.update', $order->id) }}" method="POST">
                    @csrf
                    <div style="display:flex;gap:10px;">
                        <input type="text" name="tracking_number" class="ms-input" placeholder="Ex: 1Z999AA10123456784" value="{{ $order->tracking_number }}" style="flex:1;">
                        <input type="text" name="carrier" class="ms-input" placeholder="Transporteur (optionnel)" value="{{ $order->carrier }}" style="width:180px;">
                        <button type="submit" class="btn-ms-primary" style="white-space:nowrap;">
                            <i class="fa-solid fa-floppy-disk"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            @if($order->tracking_number)
            <div style="margin-top:1rem;border-top:1px solid var(--border);padding-top:1rem;">
                <button onclick="document.getElementById('trackingForm').style.display=document.getElementById('trackingForm').style.display==='none'?'block':'none'" style="background:none;border:none;color:var(--blue);font-size:0.8rem;cursor:pointer;font-family:'DM Sans',sans-serif;">
                    <i class="fa-solid fa-pen"></i> Modifier le numéro de tracking
                </button>
                <div id="trackingForm" style="display:none;margin-top:12px;">
                    <form action="{{ route('hospital.orders.tracking.update', $order->id) }}" method="POST">
                        @csrf
                        <div style="display:flex;gap:10px;">
                            <input type="text" name="tracking_number" class="ms-input" placeholder="Numéro de tracking" value="{{ $order->tracking_number }}" style="flex:1;">
                            <input type="text" name="carrier" class="ms-input" placeholder="Transporteur" value="{{ $order->carrier }}" style="width:180px;">
                            <button type="submit" class="btn-ms-primary" style="white-space:nowrap;font-size:0.82rem;">
                                <i class="fa-solid fa-floppy-disk"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- Récapitulatif commande --}}
    <div class="ms-card">
        <div class="ms-card-header">
            <span class="ms-card-title"><i class="fa-solid fa-file-invoice" style="color:var(--blue);margin-right:8px"></i>Commande</span>
        </div>
        <div class="ms-card-body">
            @php
                $pills  = ['pending'=>'pill-pending','validated'=>'pill-validated','confirmed'=>'pill-confirmed','delivered'=>'pill-delivered','cancelled'=>'pill-cancelled'];
                $labels = ['pending'=>'En attente','validated'=>'Validée','confirmed'=>'Confirmée','delivered'=>'Livrée','cancelled'=>'Annulée'];
            @endphp
            <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border);">
                    <span style="color:var(--text3);font-size:0.8rem;">Référence</span>
                    <span style="font-weight:700;color:var(--blue-dark);font-family:monospace;font-size:0.82rem;">{{ $order->reference }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border);">
                    <span style="color:var(--text3);font-size:0.8rem;">Statut</span>
                    <span class="ms-pill {{ $pills[$order->status] ?? 'pill-pending' }}">{{ $labels[$order->status] ?? $order->status }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border);">
                    <span style="color:var(--text3);font-size:0.8rem;">Fournisseur</span>
                    <span style="font-weight:600;font-size:0.85rem;">{{ $order->supplier->name ?? '—' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border);">
                    <span style="color:var(--text3);font-size:0.8rem;">Total</span>
                    <span style="font-weight:700;font-size:1rem;">{{ number_format($order->total, 2) }} DH</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border);">
                    <span style="color:var(--text3);font-size:0.8rem;">Transporteur</span>
                    <span style="font-size:0.85rem;font-weight:500;">{{ $order->carrier ?? '—' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;">
                    <span style="color:var(--text3);font-size:0.8rem;">Commandé le</span>
                    <span style="font-size:0.82rem;color:var(--text2);">{{ $order->created_at->format('d/m/Y') }}</span>
                </div>
            </div>

            {{-- Étapes visuelles --}}
            <div style="margin-top:1.4rem;padding-top:1.2rem;border-top:1px solid var(--border);">
                <div style="font-size:0.72rem;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px;">Progression</div>
                @php
                    $stepsLocal  = ['pending'=>1,'validated'=>2,'confirmed'=>3,'delivered'=>4];
                    $currentLocal = $stepsLocal[$order->status] ?? 1;
                    $stepsInfo = [
                        ['label'=>'Soumise',   'icon'=>'fa-file-invoice'],
                        ['label'=>'Validée',   'icon'=>'fa-shield-halved'],
                        ['label'=>'Confirmée', 'icon'=>'fa-handshake'],
                        ['label'=>'Livrée',    'icon'=>'fa-box-open'],
                    ];
                @endphp
                @foreach($stepsInfo as $i => $st)
                <div style="display:flex;gap:10px;align-items:center;margin-bottom:10px;">
                    <div style="width:28px;height:28px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:11px;
                        background:{{ $currentLocal >= $i+1 ? 'var(--blue)' : 'var(--bg)' }};
                        color:{{ $currentLocal >= $i+1 ? 'white' : 'var(--text3)' }};
                        border:2px solid {{ $currentLocal >= $i+1 ? 'var(--blue)' : 'var(--border)' }};">
                        <i class="fa-solid {{ $st['icon'] }}" style="font-size:10px;"></i>
                    </div>
                    <span style="font-size:0.8rem;font-weight:{{ $currentLocal >= $i+1 ? '600' : '400' }};color:{{ $currentLocal >= $i+1 ? 'var(--text)' : 'var(--text3)' }};">
                        {{ $st['label'] }}
                    </span>
                    @if($currentLocal === $i+1)
                    <span style="margin-left:auto;font-size:0.65rem;background:var(--blue-soft);color:var(--blue-dark);padding:2px 7px;border-radius:20px;font-weight:700;">Actuel</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

@if(session('success'))
<div style="background:var(--mint);color:var(--mint-dark);padding:10px 16px;border-radius:11px;margin-bottom:1rem;font-size:0.84rem;font-weight:600;">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

@endsection