@extends('layouts.admin')
@section('title','Fournisseurs')
@section('page-title','Gestion des fournisseurs')
@section('page-subtitle','Sociétés fournisseurs de matériels médicaux')

@section('content')
<div class="page-header">
  <div><h2>Fournisseurs</h2><p>{{ count($suppliers) }} fournisseurs enregistrés</p></div>
  <div class="d-flex gap-2">
    <a href="#" class="btn-ms-secondary"><i class="fa-solid fa-file-export"></i> Exporter</a>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.05s">
      <div class="kpi-blob" style="background:var(--blue)"></div>
      <div class="kpi-icon" style="background:var(--blue-soft);color:var(--blue-dark)"><i class="fa-solid fa-truck-medical"></i></div>
      <div class="kpi-label">Total fournisseurs</div>
      <div class="kpi-value">{{ count($suppliers) }}</div>
      <div class="kpi-trend" style="color:var(--mint-dark)"><i class="fa-solid fa-arrow-trend-up"></i> +2 ce mois</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.10s">
      <div class="kpi-blob" style="background:var(--mint-dark)"></div>
      <div class="kpi-icon" style="background:var(--mint);color:var(--mint-dark)"><i class="fa-solid fa-badge-check"></i></div>
      <div class="kpi-label">Vérifiés</div>
      <div class="kpi-value">{{ $suppliers->where('is_verified',true)->count() }}</div>
      <div class="kpi-trend" style="color:var(--text3)">Validés par admin</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.15s">
      <div class="kpi-blob" style="background:var(--peach-dark)"></div>
      <div class="kpi-icon" style="background:var(--peach);color:var(--peach-dark)"><i class="fa-solid fa-clock"></i></div>
      <div class="kpi-label">En attente</div>
      <div class="kpi-value">{{ $suppliers->where('is_verified',false)->count() }}</div>
      <div class="kpi-trend" style="color:var(--peach-dark)">À valider</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.20s">
      <div class="kpi-blob" style="background:var(--lavender-dark)"></div>
      <div class="kpi-icon" style="background:var(--lavender);color:var(--lavender-dark)"><i class="fa-solid fa-star"></i></div>
      <div class="kpi-label">Note moyenne</div>
      <div class="kpi-value">{{ number_format($suppliers->avg('rating'),1) }}</div>
      <div class="kpi-trend" style="color:var(--text3)">Sur 5.0</div>
    </div>
  </div>
</div>

<div class="ms-card" style="animation-delay:0.25s">
  <div class="ms-card-header">
    <span class="ms-card-title"><i class="fa-solid fa-truck-medical" style="color:var(--blue);margin-right:8px"></i>Liste des fournisseurs</span>
    <div class="d-flex gap-2">
      <select class="ms-input ms-select" style="width:160px;padding:7px 36px 7px 12px" onchange="filterVerif(this.value)">
        <option value="">Tous</option>
        <option value="1">Vérifiés</option>
        <option value="0">En attente</option>
      </select>
      <div class="search-box" style="width:200px">
        <i class="fa-solid fa-magnifying-glass" style="color:var(--text3);font-size:12px"></i>
        <input type="text" placeholder="Rechercher..." oninput="searchTable(this.value,'suppTable')"/>
      </div>
    </div>
  </div>
  <div class="ms-card-body" style="padding:0">
    <table class="ms-table" id="suppTable">
      <thead>
        <tr><th>#</th><th>Société</th><th>Email</th><th>Téléphone</th><th>Note</th><th>Statut</th><th>Enregistré le</th><th style="text-align:right">Actions</th></tr>
      </thead>
      <tbody>
        @forelse($suppliers as $s)
        <tr data-name="{{ strtolower($s->company_name.' '.$s->email) }}" data-verified="{{ $s->is_verified ? '1' : '0' }}">
          <td style="color:var(--text3);font-family:monospace;font-size:12px">#{{ $s->id }}</td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div style="width:36px;height:36px;border-radius:10px;background:var(--lavender);display:flex;align-items:center;justify-content:center;color:var(--lavender-dark);font-size:13px;font-weight:600">{{ strtoupper(substr($s->company_name,0,2)) }}</div>
              <div>
                <div style="font-size:13px;font-weight:500;color:var(--text)">{{ $s->company_name }}</div>
                <div style="font-size:11px;color:var(--text3)">{{ $s->registration_number ?? 'N/A' }}</div>
              </div>
            </div>
          </td>
          <td style="font-size:12px;color:var(--text2)">{{ $s->email }}</td>
          <td style="font-size:12px;color:var(--text3)">{{ $s->phone ?? '—' }}</td>
          <td>
            <div class="d-flex align-items-center gap-1">
              <i class="fa-solid fa-star" style="color:#f59e0b;font-size:11px"></i>
              <span style="font-size:13px;font-weight:500;color:var(--text)">{{ number_format($s->rating,1) }}</span>
            </div>
          </td>
          <td>
            @if($s->is_verified)<span class="ms-pill pill-verified">Vérifié</span>
            @else<span class="ms-pill pill-pending">En attente</span>@endif
          </td>
          <td style="font-size:12px;color:var(--text3)">{{ \Carbon\Carbon::parse($s->created_at)->format('d/m/Y') }}</td>
          <td>
            <div class="d-flex justify-content-end gap-1">
              <a href="{{ route('admin.suppliers.show',$s->id) }}" class="icon-btn" style="width:30px;height:30px"><i class="fa-solid fa-eye" style="font-size:12px"></i></a>
              @if(!$s->is_verified)
              <form action="{{ route('admin.suppliers.verify',$s->id) }}" method="POST" style="display:inline">
                @csrf @method('PUT')
                <button type="submit" class="icon-btn" style="width:30px;height:30px;background:var(--mint);border-color:rgba(60,191,153,0.2);color:var(--mint-dark)" title="Valider"><i class="fa-solid fa-check" style="font-size:12px"></i></button>
              </form>
              @endif
              <form action="{{ route('admin.suppliers.destroy',$s->id) }}" method="POST" onsubmit="return confirm('Supprimer ?')" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" class="icon-btn" style="width:30px;height:30px;background:var(--peach);border-color:rgba(224,112,80,0.2);color:var(--peach-dark)" title="Supprimer"><i class="fa-solid fa-trash" style="font-size:11px"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:48px;color:var(--text3)"><i class="fa-solid fa-truck-slash" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.4"></i>Aucun fournisseur trouvé</td></tr>
        @endforelse
      </tbody>
    </table>
    @if(method_exists($suppliers,'links'))
    <div style="padding:16px 22px;border-top:1px solid var(--border);display:flex;justify-content:flex-end">{{ $suppliers->links() }}</div>
    @endif
  </div>
</div>
@endsection

@section('scripts')
<script>
function searchTable(q,id){ q=q.toLowerCase(); document.querySelectorAll('#'+id+' tbody tr').forEach(r=>r.style.display=r.dataset.name.includes(q)?'':'none'); }
function filterVerif(v){ document.querySelectorAll('#suppTable tbody tr').forEach(r=>r.style.display=(!v||r.dataset.verified===v)?'':'none'); }
document.querySelectorAll('#suppTable tbody tr').forEach((r,i)=>r.style.animation=`fadeUp 0.3s ease ${0.05*i+0.3}s both`);
</script>
@endsection