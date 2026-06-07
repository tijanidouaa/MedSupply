@extends('layouts.admin')
@section('title', "Logs d'audit")
@section('page-title', "Logs d'audit")
@section('page-subtitle', "MedSupply · Historique des actions")

@section('content')
<style>
.filters-bar { background: var(--card-bg); border-radius: var(--radius); box-shadow: var(--shadow); border: 1px solid var(--border); padding: 1rem 1.4rem; margin-bottom: 1.4rem; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
.action-badge { padding: 3px 9px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
.action-created { background: var(--mint); color: var(--mint-dark); }
.action-updated { background: var(--blue-soft); color: var(--blue-dark); }
.action-deleted { background: var(--peach); color: var(--peach-dark); }
.detail-btn { background: none; border: 1.5px solid var(--border); border-radius: 8px; padding: 4px 10px; font-size: 0.72rem; color: var(--text2); cursor: pointer; transition: all 0.2s; font-family: 'DM Sans', sans-serif; }
.detail-btn:hover { background: var(--blue-soft); color: var(--blue-dark); border-color: var(--blue); }
.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 999; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
.modal-overlay.open { display: flex; }
.modal-box { background: var(--card-bg); border-radius: 18px; padding: 1.8rem; width: 520px; max-width: 95vw; box-shadow: 0 20px 60px rgba(0,0,0,0.2); animation: fadeUp 0.3s ease; }
.modal-title { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 600; color: var(--text); margin-bottom: 1.2rem; display: flex; justify-content: space-between; align-items: center; }
.json-label { font-size: 0.72rem; font-weight: 700; color: var(--text3); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
.json-block { background: var(--bg); border-radius: 10px; padding: 12px; font-size: 0.74rem; font-family: monospace; color: var(--text2); max-height: 180px; overflow-y: auto; white-space: pre-wrap; border: 1px solid var(--border); margin-bottom: 1rem; }
</style>

<!-- Filtres -->
<div class="filters-bar">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;width:100%;align-items:center;">
        <input type="text" name="search" class="ms-input" style="width:200px;" placeholder="Rechercher un utilisateur..." value="{{ request('search') }}">
        <select name="action" class="ms-input ms-select" style="width:150px;">
            <option value="">Toutes les actions</option>
            <option value="created" {{ request('action')=='created'?'selected':'' }}>Créé</option>
            <option value="updated" {{ request('action')=='updated'?'selected':'' }}>Modifié</option>
            <option value="deleted" {{ request('action')=='deleted'?'selected':'' }}>Supprimé</option>
        </select>
        <select name="model_type" class="ms-input ms-select" style="width:160px;">
            <option value="">Tous les modèles</option>
            <option value="User" {{ request('model_type')=='User'?'selected':'' }}>Utilisateur</option>
            <option value="Order" {{ request('model_type')=='Order'?'selected':'' }}>Commande</option>
            <option value="Hospital" {{ request('model_type')=='Hospital'?'selected':'' }}>Hôpital</option>
            <option value="Supplier" {{ request('model_type')=='Supplier'?'selected':'' }}>Fournisseur</option>
        </select>
        <button type="submit" class="btn-ms-primary" style="padding:8px 16px;">
            <i class="fa-solid fa-magnifying-glass"></i> Filtrer
        </button>
        <a href="{{ route('admin.audit-logs') }}" class="btn-ms-secondary" style="padding:8px 16px;">
            <i class="fa-solid fa-rotate"></i> Réinitialiser
        </a>
        <span style="margin-left:auto;font-size:0.78rem;color:var(--text3);">{{ $logs->total() }} entrée(s)</span>
    </form>
</div>

<!-- Table -->
<div class="ms-card">
    <div class="ms-card-header">
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:32px;height:32px;border-radius:9px;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;color:var(--blue-dark);">
                <i class="fa-solid fa-shield-halved" style="font-size:13px;"></i>
            </div>
            <span class="ms-card-title">Journal des actions</span>
        </div>
    </div>
    <table class="ms-table">
        <thead>
            <tr>
                <th>Date & Heure</th>
                <th>Utilisateur</th>
                <th>Action</th>
                <th>Modèle</th>
                <th>ID</th>
                <th>Adresse IP</th>
                <th>Détails</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td style="white-space:nowrap;">
                    <div style="font-size:0.8rem;font-weight:600;color:var(--text);">{{ $log->created_at->format('d/m/Y') }}</div>
                    <div style="font-size:0.72rem;color:var(--text3);">{{ $log->created_at->format('H:i:s') }}</div>
                </td>
                <td>
                    @if($log->user)
                        <div style="font-weight:600;font-size:0.82rem;">{{ $log->user->name }}</div>
                        <div style="font-size:0.72rem;color:var(--text3);">{{ $log->user->email }}</div>
                    @else
                        <span class="ms-pill" style="background:var(--bg);color:var(--text3);">Système</span>
                    @endif
                </td>
                <td>
                    <span class="action-badge action-{{ $log->action }}">
                        @if($log->action === 'created') <i class="fa-solid fa-plus"></i> Créé
                        @elseif($log->action === 'updated') <i class="fa-solid fa-pen"></i> Modifié
                        @elseif($log->action === 'deleted') <i class="fa-solid fa-trash"></i> Supprimé
                        @else {{ $log->action }}
                        @endif
                    </span>
                </td>
                <td>
                    <span class="ms-pill pill-admin">{{ $log->model_type }}</span>
                </td>
                <td style="color:var(--text3);font-size:0.8rem;">#{{ $log->model_id }}</td>
                <td style="font-size:0.78rem;color:var(--text3);">{{ $log->ip_address ?? '—' }}</td>
                <td>
                    @if($log->old_values || $log->new_values)
                    <button class="detail-btn" onclick="showDetail({{ json_encode($log->old_values) }}, {{ json_encode($log->new_values) }})">
                        <i class="fa-solid fa-eye"></i> Voir
                    </button>
                    @else
                        <span style="color:var(--text3);">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:3rem;color:var(--text3);">
                    <i class="fa-solid fa-inbox" style="font-size:2.5rem;margin-bottom:10px;display:block;opacity:0.4;"></i>
                    Aucun log trouvé
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($logs->hasPages())
    <div style="padding:1rem 1.4rem;border-top:1px solid var(--border);">
        {{ $logs->withQueryString()->links() }}
    </div>
    @endif
</div>

<!-- Modal -->
<div class="modal-overlay" id="detailModal" onclick="closeModal(event)">
    <div class="modal-box">
        <div class="modal-title">
            <span><i class="fa-solid fa-code" style="color:var(--blue);margin-right:8px;"></i>Détails de l'action</span>
            <button onclick="document.getElementById('detailModal').classList.remove('open')"
                style="background:none;border:none;cursor:pointer;color:var(--text3);font-size:1.1rem;padding:4px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="json-label">Avant la modification</div>
        <div class="json-block" id="oldValues">—</div>
        <div class="json-label">Après la modification</div>
        <div class="json-block" id="newValues">—</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showDetail(oldVal, newVal) {
    document.getElementById('oldValues').textContent = oldVal ? JSON.stringify(oldVal, null, 2) : '—';
    document.getElementById('newValues').textContent = newVal ? JSON.stringify(newVal, null, 2) : '—';
    document.getElementById('detailModal').classList.add('open');
}
function closeModal(e) {
    if (e.target.id === 'detailModal') {
        document.getElementById('detailModal').classList.remove('open');
    }
}
</script>
@endsection