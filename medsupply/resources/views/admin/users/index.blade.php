@extends('layouts.admin')

@section('title', 'Utilisateurs')
@section('page-title', 'Gestion des utilisateurs')
@section('page-subtitle', 'Tous les comptes enregistrés sur la plateforme')

@section('content')

<div class="page-header">
  <div class="page-header-left">
    <h2>Utilisateurs</h2>
    <p>{{ $users->total() ?? count($users) }} comptes enregistrés</p>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.users.export') }}" class="btn-ms-secondary"><i class="fa-solid fa-file-export"></i> Exporter</a>
    <a href="{{ route('admin.users.create') }}" class="btn-ms-primary"><i class="fa-solid fa-plus"></i> Nouvel utilisateur</a>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.05s">
      <div class="kpi-blob" style="background:var(--blue)"></div>
      <div class="kpi-icon" style="background:var(--blue-soft);color:var(--blue-dark)"><i class="fa-solid fa-users"></i></div>
      <div class="kpi-label">Total</div>
      <div class="kpi-value">{{ $users->total() ?? count($users) }}</div>
      <div class="kpi-trend" style="color:var(--mint-dark)"><i class="fa-solid fa-arrow-trend-up"></i> +2 ce mois</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.10s">
      <div class="kpi-blob" style="background:var(--lavender-dark)"></div>
      <div class="kpi-icon" style="background:var(--lavender);color:var(--lavender-dark)"><i class="fa-solid fa-shield-halved"></i></div>
      <div class="kpi-label">Administrateurs</div>
      <div class="kpi-value">{{ $users->where('role','admin')->count() }}</div>
      <div class="kpi-trend" style="color:var(--text3)">Accès complet</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.15s">
      <div class="kpi-blob" style="background:var(--blue)"></div>
      <div class="kpi-icon" style="background:var(--blue-soft);color:var(--blue-dark)"><i class="fa-solid fa-hospital-user"></i></div>
      <div class="kpi-label">Chefs hôpital</div>
      <div class="kpi-value">{{ $users->where('role','hospital_chief')->count() }}</div>
      <div class="kpi-trend" style="color:var(--text3)">Gestion stock</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.20s">
      <div class="kpi-blob" style="background:var(--mint-dark)"></div>
      <div class="kpi-icon" style="background:var(--mint);color:var(--mint-dark)"><i class="fa-solid fa-building-store"></i></div>
      <div class="kpi-label">Fournisseurs</div>
      <div class="kpi-value">{{ $users->where('role','supplier')->count() }}</div>
      <div class="kpi-trend" style="color:var(--text3)">Catalogue produits</div>
    </div>
  </div>
</div>

<div class="ms-card" style="animation-delay:0.25s">
  <div class="ms-card-header">
    <span class="ms-card-title"><i class="fa-solid fa-list" style="color:var(--blue);margin-right:8px"></i>Liste des utilisateurs</span>
    <div class="d-flex gap-2 align-items-center">
      <select class="ms-input ms-select" style="width:160px;padding:7px 36px 7px 12px" onchange="filterRole(this.value)">
        <option value="">Tous les rôles</option>
        <option value="admin">Admin</option>
        <option value="hospital_chief">Chef hôpital</option>
        <option value="supplier">Fournisseur</option>
      </select>
      <div class="search-box" style="width:200px">
        <i class="fa-solid fa-magnifying-glass" style="color:var(--text3);font-size:12px"></i>
        <input type="text" placeholder="Rechercher..." oninput="searchTable(this.value)"/>
      </div>
    </div>
  </div>
  <div class="ms-card-body" style="padding:0">
    <div style="overflow-x:auto">
      <table class="ms-table" id="usersTable">
        <thead>
          <tr>
            <th>#</th><th>Utilisateur</th><th>Email</th><th>Rôle</th><th>Statut</th><th>Dernière connexion</th><th>Créé le</th><th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
          <tr data-role="{{ $user->role }}" data-name="{{ strtolower($user->name.' '.$user->email) }}">
            <td style="color:var(--text3);font-size:12px;font-family:monospace">#{{ $user->id }}</td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--lavender-dark));display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:white;flex-shrink:0">{{ strtoupper(substr($user->name,0,2)) }}</div>
                <span style="font-size:13px;font-weight:500;color:var(--text)">{{ $user->name }}</span>
              </div>
            </td>
            <td style="color:var(--text2)">{{ $user->email }}</td>
            <td>
              @if($user->role==='admin') <span class="ms-pill pill-admin"><i class="fa-solid fa-shield-halved" style="font-size:10px"></i> Admin</span>
              @elseif($user->role==='hospital_chief') <span class="ms-pill pill-chef"><i class="fa-solid fa-hospital" style="font-size:10px"></i> Chef hôpital</span>
              @elseif($user->role==='supplier') <span class="ms-pill pill-supplier"><i class="fa-solid fa-truck" style="font-size:10px"></i> Fournisseur</span>
              @else <span class="ms-pill" style="background:var(--bg);color:var(--text3)">{{ $user->role }}</span>
              @endif
            </td>
            <td>
              @if($user->is_active) <span class="ms-pill pill-active">Actif</span>
              @else <span class="ms-pill pill-inactive">Inactif</span>
              @endif
            </td>
            <td style="color:var(--text3);font-size:12px">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Jamais' }}</td>
            <td style="color:var(--text3);font-size:12px">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
            <td>
              <div class="d-flex justify-content-end gap-1">
                <a href="{{ route('admin.users.show',$user->id) }}" class="topbar-icon-btn" style="width:30px;height:30px" title="Voir"><i class="fa-solid fa-eye" style="font-size:12px"></i></a>
                <a href="{{ route('admin.users.edit',$user->id) }}" class="topbar-icon-btn" style="width:30px;height:30px" title="Modifier"><i class="fa-solid fa-pen" style="font-size:12px"></i></a>
                <form action="{{ route('admin.users.destroy',$user->id) }}" method="POST" onsubmit="return confirm('Supprimer ?')" style="display:inline">
                  @csrf @method('DELETE')
                  <button type="submit" class="topbar-icon-btn" style="width:30px;height:30px;background:var(--peach);border-color:rgba(224,112,80,0.2);color:var(--peach-dark)" title="Supprimer">
                    <i class="fa-solid fa-trash" style="font-size:11px"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center;padding:48px;color:var(--text3)">
            <i class="fa-solid fa-users-slash" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.4"></i>Aucun utilisateur trouvé
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if(method_exists($users,'links'))
    <div style="padding:16px 22px;border-top:1px solid var(--border);display:flex;justify-content:flex-end">{{ $users->links() }}</div>
    @endif
  </div>
</div>
@endsection

@section('scripts')
<script>
function filterRole(r){ document.querySelectorAll('#usersTable tbody tr').forEach(row=>{ row.style.display=(!r||row.dataset.role===r)?'':'none'; }); }
function searchTable(q){ q=q.toLowerCase(); document.querySelectorAll('#usersTable tbody tr').forEach(row=>{ row.style.display=row.dataset.name.includes(q)?'':'none'; }); }
document.querySelectorAll('#usersTable tbody tr').forEach((row,i)=>{ row.style.animation=`fadeUp 0.3s ease ${0.05*i+0.3}s both`; });
</script>
@endsection