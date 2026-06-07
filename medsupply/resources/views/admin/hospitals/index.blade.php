@extends('layouts.admin')
@section('title','Hôpitaux')
@section('page-title','Gestion des hôpitaux')
@section('page-subtitle','Tous les établissements enregistrés sur la plateforme')

@section('content')
<div class="page-header">
  <div><h2>Hôpitaux</h2><p>{{ count($hospitals) }} établissements enregistrés</p></div>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.hospitals.export') }}" class="btn-ms-secondary">
      <i class="fa-solid fa-file-export"></i> Exporter
    </a>
    <a href="{{ route('admin.hospitals.create') }}" class="btn-ms-primary"><i class="fa-solid fa-plus"></i> Nouvel hôpital</a>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.05s">
      <div class="kpi-blob" style="background:var(--blue)"></div>
      <div class="kpi-icon" style="background:var(--blue-soft);color:var(--blue-dark)"><i class="fa-solid fa-hospital"></i></div>
      <div class="kpi-label">Total hôpitaux</div>
      <div class="kpi-value">{{ count($hospitals) }}</div>
      <div class="kpi-trend" style="color:var(--mint-dark)"><i class="fa-solid fa-arrow-trend-up"></i> +3 ce mois</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.10s">
      <div class="kpi-blob" style="background:var(--mint-dark)"></div>
      <div class="kpi-icon" style="background:var(--mint);color:var(--mint-dark)"><i class="fa-solid fa-circle-check"></i></div>
      <div class="kpi-label">Actifs</div>
      <div class="kpi-value">{{ $hospitals->where('is_active',true)->count() }}</div>
      <div class="kpi-trend" style="color:var(--text3)">En service</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.15s">
      <div class="kpi-blob" style="background:var(--lavender-dark)"></div>
      <div class="kpi-icon" style="background:var(--lavender);color:var(--lavender-dark)"><i class="fa-solid fa-city"></i></div>
      <div class="kpi-label">Villes couvertes</div>
      <div class="kpi-value">{{ $hospitals->pluck('city')->unique()->count() }}</div>
      <div class="kpi-trend" style="color:var(--text3)">Régions</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.20s">
      <div class="kpi-blob" style="background:var(--peach-dark)"></div>
      <div class="kpi-icon" style="background:var(--peach);color:var(--peach-dark)"><i class="fa-solid fa-circle-xmark"></i></div>
      <div class="kpi-label">Inactifs</div>
      <div class="kpi-value">{{ $hospitals->where('is_active',false)->count() }}</div>
      <div class="kpi-trend" style="color:var(--text3)">Suspendus</div>
    </div>
  </div>
</div>

<div class="ms-card" style="animation-delay:0.25s">
  <div class="ms-card-header">
    <span class="ms-card-title"><i class="fa-solid fa-hospital" style="color:var(--blue);margin-right:8px"></i>Liste des hôpitaux</span>
    <div class="d-flex gap-2">
      <div class="search-box" style="width:200px">
        <i class="fa-solid fa-magnifying-glass" style="color:var(--text3);font-size:12px"></i>
        <input type="text" placeholder="Rechercher..." oninput="searchTable(this.value,'hospTable')"/>
      </div>
    </div>
  </div>
  <div class="ms-card-body" style="padding:0">
    <table class="ms-table" id="hospTable">
      <thead>
        <tr><th>#</th><th>Établissement</th><th>Ville</th><th>Email contact</th><th>Téléphone</th><th>Statut</th><th style="text-align:right">Actions</th></tr>
      </thead>
      <tbody>
        @forelse($hospitals as $h)
        <tr data-name="{{ strtolower($h->name.' '.$h->city) }}">
          <td style="color:var(--text3);font-family:monospace;font-size:12px">#{{ $h->id }}</td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div style="width:36px;height:36px;border-radius:10px;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;color:var(--blue-dark)"><i class="fa-solid fa-hospital" style="font-size:15px"></i></div>
              <div>
                <div style="font-size:13px;font-weight:500;color:var(--text)">{{ $h->name }}</div>
                <div style="font-size:11px;color:var(--text3)">{{ Str::limit($h->address,40) }}</div>
              </div>
            </div>
          </td>
          <td><span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;color:var(--text2)"><i class="fa-solid fa-location-dot" style="color:var(--blue)"></i>{{ $h->city }}</span></td>
          <td style="font-size:12px;color:var(--text2)">{{ $h->contact_email }}</td>
          <td style="font-size:12px;color:var(--text3)">{{ $h->phone ?? '—' }}</td>
          <td>@if($h->is_active)<span class="ms-pill pill-active">Actif</span>@else<span class="ms-pill pill-inactive">Inactif</span>@endif</td>
          <td>
            <div class="d-flex justify-content-end gap-1">
              <a href="{{ route('admin.hospitals.show',$h->id) }}" class="icon-btn" style="width:30px;height:30px" title="Voir"><i class="fa-solid fa-eye" style="font-size:12px"></i></a>
              <a href="{{ route('admin.hospitals.edit',$h->id) }}" class="icon-btn" style="width:30px;height:30px" title="Modifier"><i class="fa-solid fa-pen" style="font-size:12px"></i></a>
              <form action="{{ route('admin.hospitals.destroy',$h->id) }}" method="POST" onsubmit="return confirm('Supprimer ?')" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" class="icon-btn" style="width:30px;height:30px;background:var(--peach);border-color:rgba(224,112,80,0.2);color:var(--peach-dark)" title="Supprimer"><i class="fa-solid fa-trash" style="font-size:11px"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:48px;color:var(--text3)"><i class="fa-solid fa-hospital-slash" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.4"></i>Aucun hôpital trouvé</td></tr>
        @endforelse
      </tbody>
    </table>
    @if(method_exists($hospitals,'links'))
    <div style="padding:16px 22px;border-top:1px solid var(--border);display:flex;justify-content:flex-end">{{ $hospitals->links() }}</div>
    @endif
  </div>
</div>
@endsection

@section('scripts')
<script>
function searchTable(q,id){ q=q.toLowerCase(); document.querySelectorAll('#'+id+' tbody tr').forEach(r=>{ r.style.display=r.dataset.name.includes(q)?'':'none'; }); }
document.querySelectorAll('#hospTable tbody tr').forEach((r,i)=>r.style.animation=`fadeUp 0.3s ease ${0.05*i+0.3}s both`);
</script>
@endsection