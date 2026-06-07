@extends('layouts.supplier')
@section('title', 'Détail Commande')
@section('page-title', 'Détail Commande')
@section('page-subtitle', 'MedSupply · {{ $order->reference }}')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.4rem;">
    <div>
        <h2 style="font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600;color:var(--text);">{{ $order->reference }}</h2>
        <p style="font-size:0.8rem;color:var(--text3);">Détails de la commande</p>
    </div>
    <a href="{{ route('supplier.orders.index') }}" class="btn-ms-secondary">
        <i class="fa-solid fa-arrow-left"></i> Retour
    </a>
</div>

@if(session('success'))
<div style="background:var(--mint);color:var(--mint-dark);padding:10px 16px;border-radius:11px;margin-bottom:1rem;font-size:0.84rem;font-weight:600;">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

@php
    $pills  = ['pending'=>'background:#fff3cd;color:#856404','validated'=>'background:var(--blue-soft);color:var(--blue-dark)','confirmed'=>'background:var(--lavender);color:var(--lavender-dark)','shipped'=>'background:var(--blue-soft);color:var(--blue-dark)','delivered'=>'background:var(--mint);color:var(--mint-dark)','cancelled'=>'background:var(--peach);color:var(--peach-dark)'];
    $labels = ['pending'=>'En attente','validated'=>'Validée','confirmed'=>'Confirmée','shipped'=>'Expédiée','delivered'=>'Livrée','cancelled'=>'Annulée'];
    $steps = [
        ['key'=>'pending',   'label'=>'Commande reçue', 'icon'=>'fa-file-invoice'],
        ['key'=>'confirmed', 'label'=>'Confirmée',       'icon'=>'fa-circle-check'],
        ['key'=>'shipped',   'label'=>'Expédiée',        'icon'=>'fa-truck'],
        ['key'=>'delivered', 'label'=>'Livrée',          'icon'=>'fa-box-open'],
    ];
    $stepOrder = ['pending'=>1,'validated'=>1,'confirmed'=>2,'shipped'=>3,'delivered'=>4,'cancelled'=>0];
    $current   = $stepOrder[$order->status] ?? 1;
@endphp

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.4rem;">

    {{-- Colonne gauche --}}
    <div style="display:flex;flex-direction:column;gap:1.2rem;">

        {{-- Infos commande --}}
        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title">Informations de la commande</span>
                <span style="padding:3px 9px;border-radius:20px;font-size:0.7rem;font-weight:700;{{ $pills[$order->status] ?? '' }}">
                    {{ $labels[$order->status] ?? $order->status }}
                </span>
            </div>
            <div style="padding:1.4rem;">
                @foreach([
                    ['Référence', $order->reference],
                    ['Hôpital',   $order->hospital->name ?? '—'],
                    ['Total',     number_format($order->total, 2) . ' DH'],
                    ['Date',      $order->created_at->format('d/m/Y H:i')],
                ] as $row)
                <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border);font-size:0.83rem;">
                    <span style="color:var(--text3);">{{ $row[0] }}</span>
                    <span style="font-weight:600;">{{ $row[1] }}</span>
                </div>
                @endforeach
                @if($order->tracking_number)
                <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border);font-size:0.83rem;">
                    <span style="color:var(--text3);">Numéro de tracking</span>
                    <span style="font-weight:600;font-family:monospace;color:var(--blue-dark);">{{ $order->tracking_number }}</span>
                </div>
                @endif
                @if($order->carrier)
                <div style="display:flex;justify-content:space-between;padding:12px 0;font-size:0.83rem;">
                    <span style="color:var(--text3);">Société de livraison</span>
                    <span style="font-weight:600;">{{ $order->carrier }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Formulaire tracking --}}
        @if(in_array($order->status, ['confirmed', 'shipped']))
        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title">
                    <i class="fa-solid fa-truck" style="color:var(--blue);margin-right:8px;"></i>
                    Informations de livraison
                </span>
            </div>
            <div class="ms-card-body" style="padding:1.4rem;">
                <form method="POST" action="{{ route('supplier.orders.tracking.update', $order->id) }}">
                    @csrf
                    <div style="display:flex;flex-direction:column;gap:14px;">
                        <div>
                            <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Société de livraison</label>
                            <select name="carrier" class="ms-input ms-select" required>
                                <option value="">Choisir une société...</option>
                                @foreach(['Amana','Aramex','DHL','FedEx','UPS','BMCE Tawssil','Chronopost Maroc','CTM','Autre'] as $carrier)
                                <option value="{{ $carrier }}" {{ $order->carrier === $carrier ? 'selected' : '' }}>{{ $carrier }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text2);margin-bottom:6px;">Numéro de tracking</label>
                            <input type="text" name="tracking_number" class="ms-input"
                                   placeholder="Ex: 1Z999AA10123456784"
                                   value="{{ $order->tracking_number }}">
                        </div>
                        <button type="submit" class="btn-ms-primary" style="justify-content:center;">
                            <i class="fa-solid fa-floppy-disk"></i> Enregistrer les infos de livraison
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

    {{-- Colonne droite --}}
    <div style="display:flex;flex-direction:column;gap:1.2rem;">

        {{-- Actions --}}
        @if(!in_array($order->status, ['delivered','cancelled']))
        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title"><i class="fa-solid fa-bolt" style="color:var(--blue);margin-right:8px;"></i>Actions</span>
            </div>
            <div class="ms-card-body" style="display:flex;flex-direction:column;gap:10px;padding:1rem;">
                @if($order->status === 'pending')
                <button type="button" onclick="openModal('modalConfirm')" class="btn-ms-primary" style="justify-content:center;">
                    <i class="fa-solid fa-circle-check"></i> Confirmer la commande
                </button>
                @elseif(in_array($order->status, ['validated','confirmed']))
                <button type="button" onclick="openModal('modalShip')" class="btn-ms-primary" style="justify-content:center;background:linear-gradient(135deg,var(--lavender-dark),#6b5bb5);">
                    <i class="fa-solid fa-truck"></i> Marquer comme expédiée
                </button>
                @elseif($order->status === 'shipped')
                <button type="button" onclick="openModal('modalDeliver')" class="btn-ms-primary" style="justify-content:center;background:linear-gradient(135deg,var(--mint-dark),#2aa87a);">
                    <i class="fa-solid fa-box-open"></i> Marquer comme livrée
                </button>
                @endif
                <button type="button" onclick="openModal('modalCancel')" class="btn-ms-secondary" style="justify-content:center;color:var(--peach-dark);border-color:rgba(224,112,80,0.3);">
                    <i class="fa-solid fa-xmark"></i> Annuler la commande
                </button>
            </div>
        </div>
        @endif

        {{-- Progression avec ligne verticale --}}
        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title">Progression</span>
            </div>
            <div class="ms-card-body" style="padding:1.4rem 1.4rem 1rem;">
                <div style="position:relative;">
                    {{-- Ligne verticale de fond --}}
                    <div style="position:absolute;left:19px;top:20px;bottom:20px;width:2px;background:var(--border);z-index:0;"></div>

                    @foreach($steps as $i => $step)
                    @php $done = $current >= $i + 1; $active = $current === $i + 1; @endphp
                    <div style="display:flex;gap:16px;align-items:flex-start;margin-bottom:{{ $i < count($steps)-1 ? '28px' : '0' }};position:relative;z-index:1;">

                        {{-- Icône --}}
                        <div style="
                            width:40px;height:40px;border-radius:50%;
                            display:flex;align-items:center;justify-content:center;
                            font-size:14px;flex-shrink:0;
                            background:{{ $done ? 'var(--lavender-dark)' : 'var(--card-bg)' }};
                            color:{{ $done ? 'white' : 'var(--text3)' }};
                            border:2px solid {{ $done ? 'var(--lavender-dark)' : 'var(--border)' }};
                            box-shadow:{{ $active ? '0 0 0 4px rgba(139,127,212,0.2)' : 'none' }};
                        ">
                            <i class="fa-solid {{ $step['icon'] }}"></i>
                        </div>

                        {{-- Label --}}
                        <div style="padding-top:8px;">
                            <div style="font-size:0.84rem;font-weight:{{ $active ? '700' : '600' }};color:{{ $done ? 'var(--text)' : 'var(--text3)' }};">
                                {{ $step['label'] }}
                            </div>
                            @if($active)
                            <span style="display:inline-block;margin-top:4px;font-size:0.65rem;background:var(--lavender);color:var(--lavender-dark);padding:2px 8px;border-radius:20px;font-weight:700;">
                                Étape actuelle
                            </span>
                            @elseif($done)
                            <span style="display:inline-block;margin-top:4px;font-size:0.65rem;color:var(--text3);">
                                <i class="fa-solid fa-check" style="font-size:9px;"></i> Complété
                            </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Formulaires cachés --}}
<form id="formConfirm" method="POST" action="{{ route('supplier.orders.validate', $order->id) }}">@csrf @method('PUT')<input type="hidden" name="new_status" value="confirmed"></form>
<form id="formShip"    method="POST" action="{{ route('supplier.orders.validate', $order->id) }}">@csrf @method('PUT')<input type="hidden" name="new_status" value="shipped"></form>
<form id="formDeliver" method="POST" action="{{ route('supplier.orders.validate', $order->id) }}">@csrf @method('PUT')<input type="hidden" name="new_status" value="delivered"></form>
<form id="formCancel"  method="POST" action="{{ route('supplier.orders.validate', $order->id) }}">@csrf @method('PUT')<input type="hidden" name="new_status" value="cancelled"></form>

{{-- Modales --}}
<div id="modalConfirm" class="ms-modal-overlay" style="display:none;">
    <div class="ms-modal">
        <div class="ms-modal-icon" style="background:var(--mint);"><i class="fa-solid fa-circle-check" style="color:var(--mint-dark);font-size:22px;"></i></div>
        <h3 class="ms-modal-title">Confirmer la commande ?</h3>
        <p class="ms-modal-text">La commande <strong>{{ $order->reference }}</strong> sera marquée comme confirmée.</p>
        <div class="ms-modal-actions">
            <button type="button" onclick="closeModal('modalConfirm')" class="btn-ms-secondary">Annuler</button>
            <button type="button" onclick="document.getElementById('formConfirm').submit()" class="btn-ms-primary"><i class="fa-solid fa-circle-check"></i> Confirmer</button>
        </div>
    </div>
</div>

<div id="modalShip" class="ms-modal-overlay" style="display:none;">
    <div class="ms-modal">
        <div class="ms-modal-icon" style="background:var(--lavender);"><i class="fa-solid fa-truck" style="color:var(--lavender-dark);font-size:22px;"></i></div>
        <h3 class="ms-modal-title">Marquer comme expédiée ?</h3>
        <p class="ms-modal-text">Assurez-vous d'avoir renseigné le numéro de tracking et la société de livraison.</p>
        <div class="ms-modal-actions">
            <button type="button" onclick="closeModal('modalShip')" class="btn-ms-secondary">Annuler</button>
            <button type="button" onclick="document.getElementById('formShip').submit()" class="btn-ms-primary" style="background:linear-gradient(135deg,var(--lavender-dark),#6b5bb5);"><i class="fa-solid fa-truck"></i> Expédier</button>
        </div>
    </div>
</div>

<div id="modalDeliver" class="ms-modal-overlay" style="display:none;">
    <div class="ms-modal">
        <div class="ms-modal-icon" style="background:var(--mint);"><i class="fa-solid fa-box-open" style="color:var(--mint-dark);font-size:22px;"></i></div>
        <h3 class="ms-modal-title">Marquer comme livrée ?</h3>
        <p class="ms-modal-text">La commande <strong>{{ $order->reference }}</strong> sera marquée comme livrée. Action définitive.</p>
        <div class="ms-modal-actions">
            <button type="button" onclick="closeModal('modalDeliver')" class="btn-ms-secondary">Annuler</button>
            <button type="button" onclick="document.getElementById('formDeliver').submit()" class="btn-ms-primary" style="background:linear-gradient(135deg,var(--mint-dark),#2aa87a);"><i class="fa-solid fa-box-open"></i> Confirmer livraison</button>
        </div>
    </div>
</div>

<div id="modalCancel" class="ms-modal-overlay" style="display:none;">
    <div class="ms-modal">
        <div class="ms-modal-icon" style="background:var(--peach);"><i class="fa-solid fa-xmark" style="color:var(--peach-dark);font-size:22px;"></i></div>
        <h3 class="ms-modal-title">Annuler la commande ?</h3>
        <p class="ms-modal-text">La commande <strong>{{ $order->reference }}</strong> sera annulée définitivement.</p>
        <div class="ms-modal-actions">
            <button type="button" onclick="closeModal('modalCancel')" class="btn-ms-secondary">Retour</button>
            <button type="button" onclick="document.getElementById('formCancel').submit()" style="background:var(--peach-dark);color:white;border:none;border-radius:11px;padding:9px 20px;font-size:0.84rem;font-weight:600;cursor:pointer;">
                <i class="fa-solid fa-xmark"></i> Annuler
            </button>
        </div>
    </div>
</div>

<style>
.ms-modal-overlay { position:fixed;inset:0;background:rgba(15,22,36,0.55);backdrop-filter:blur(4px);z-index:1000;display:flex;align-items:center;justify-content:center;animation:fadeIn 0.2s ease; }
.ms-modal { background:var(--card-bg);border-radius:20px;padding:2rem;width:100%;max-width:420px;box-shadow:0 24px 60px rgba(0,0,0,0.2);border:1px solid var(--border);text-align:center;animation:slideUp 0.25s cubic-bezier(0.22,1,0.36,1); }
.ms-modal-icon { width:60px;height:60px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.2rem; }
.ms-modal-title { font-family:'Fraunces',serif;font-size:1.15rem;font-weight:600;color:var(--text);margin-bottom:8px; }
.ms-modal-text { font-size:0.85rem;color:var(--text2);line-height:1.6;margin-bottom:1.4rem; }
.ms-modal-actions { display:flex;gap:10px;justify-content:center; }
@keyframes fadeIn { from{opacity:0}to{opacity:1} }
@keyframes slideUp { from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)} }
</style>

@endsection

@section('scripts')
<script>
function openModal(id) { document.getElementById(id).style.display='flex'; document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).style.display='none'; document.body.style.overflow=''; }
document.addEventListener('click', e => { if(e.target.classList.contains('ms-modal-overlay')){ e.target.style.display='none'; document.body.style.overflow=''; } });
document.addEventListener('keydown', e => { if(e.key==='Escape'){ document.querySelectorAll('.ms-modal-overlay').forEach(m=>m.style.display='none'); document.body.style.overflow=''; } });
</script>
@endsection