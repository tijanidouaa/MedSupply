@extends('layouts.admin')
@section('title','Stock global')
@section('page-title','Stock global')
@section('page-subtitle','Vue globale de tous les stocks par hôpital')

@section('content')
<div class="page-header">
  <div><h2>Stock global</h2><p>Suivi en temps réel de tous les produits médicaux</p></div>
  <div class="d-flex gap-2">
    <a href="#" class="btn-ms-secondary"><i class="fa-solid fa-file-excel"></i> Exporter Excel</a>
    <a href="#" class="btn-ms-primary"><i class="fa-solid fa-plus"></i> Mouvement manuel</a>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.05s">
      <div class="kpi-blob" style="background:var(--blue)"></div>
      <div class="kpi-icon" style="background:var(--blue-soft);color:var(--blue-dark)"><i class="fa-solid fa-boxes-stacked"></i></div>
      <div class="kpi-label">Références en stock</div>
      <div class="kpi-value">{{ $stocks->count() }}</div>
      <div class="kpi-trend" style="color:var(--text3)">Produits actifs</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.10s">
      <div class="kpi-blob" style="background:#e05050)"></div>
      <div class="kpi-icon" style="background:var(--peach);color:var(--peach-dark)"><i class="fa-solid fa-triangle-exclamation"></i></div>
      <div class="kpi-label">Alertes critiques</div>
      <div class="kpi-value">{{ $stocks->filter(fn($s)=>$s->quantity <= $s->min_threshold)->count() }}</div>
      <div class="kpi-trend" style="color:var(--peach-dark)">Sous le seuil</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.15s">
      <div class="kpi-blob" style="background:var(--peach-dark)"></div>
      <div class="kpi-icon" style="background:var(--peach);color:var(--peach-dark)"><i class="fa-solid fa-calendar-xmark"></i></div>
      <div class="kpi-label">Expirent bientôt</div>
      <div class="kpi-value">{{ $stocks->filter(fn($s)=>$s->expiry_date && \Carbon\Carbon::parse($s->expiry_date)->diffInDays()<=30)->count() }}</div>
      <div class="kpi-trend" style="color:var(--peach-dark)">Dans 30 jours</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="kpi-card" style="animation-delay:0.20s">
      <div class="kpi-blob" style="background:var(--mint-dark)"></div>
      <div class="kpi-icon" style="background:var(--mint);color:var(--mint-dark)"><i class="fa-solid fa-circle-check"></i></div>
      <div class="kpi-label">Stocks sains</div>
      <div class="kpi-value">{{ $stocks->filter(fn($s)=>$s->quantity > $s->min_threshold)->count() }}</div>
      <div class="kpi-trend" style="color:var(--mint-dark)">Au-dessus du seuil</div>
    </div>
  </div>
</div>

<div class="ms-card" style="animation-delay:0.25s">
  <div class="ms-card-header">
    <span class="ms-card-title"><i class="fa-solid fa-boxes-stacked" style="color:var(--blue);margin-right:8px"></i>Inventaire complet</span>
    <div class="d-flex gap-2">
      <select class="ms-input ms-select" style="width:150px;padding:7px 36px 7px 12px" onchange="filterStatus(this.value)">
        <option value="">Tous les statuts</option>
        <option value="critical">Critique</option>
        <option value="low">Bas</option>
        <option value="ok">Normal</option>
      </select>
      <div class="search-box" style="width:200px">
        <i class="fa-solid fa-magnifying-glass" style="color:var(--text3);font-size:12px"></i>
        <input type="text" placeholder="Rechercher produit..." oninput="searchTable(this.value,'stockTable')"/>
      </div>
    </div>
  </div>
  <div class="ms-card-body" style="padding:0">
    <table class="ms-table" id="stockTable">
      <thead>
        <tr><th>Produit</th><th>Hôpital</th><th>Catégorie</th><th>Quantité</th><th>Seuil min.</th><th>Niveau</th><th>Expiration</th><th>Emplacement</th></tr>
      </thead>
      <tbody>
        @forelse($stocks as $s)
        @php
          $pct = $s->min_threshold > 0 ? min(100, round($s->quantity / max($s->min_threshold * 2, 1) * 100)) : 100;
          $status = $s->quantity == 0 ? 'critical' : ($s->quantity <= $s->min_threshold ? 'low' : 'ok');
          $barColor = $status === 'critical' ? 'var(--peach-dark)' : ($status === 'low' ? '#f59e0b' : 'var(--mint-dark)');
        @endphp
        <tr data-name="{{ strtolower(optional($s->product)->name ?? '') }}" data-status="{{ $status }}">
          <td>
            <div style="font-size:13px;font-weight:500;color:var(--text)">{{ optional($s->product)->name ?? 'N/A' }}</div>
            <div style="font-size:11px;color:var(--text3)">{{ optional($s->product)->reference ?? '' }}</div>
          </td>
          <td style="font-size:12px;color:var(--text2)">{{ optional($s->hospital)->name ?? 'N/A' }}</td>
          <td><span style="font-size:11px;background:var(--blue-soft);color:var(--blue-dark);padding:2px 8px;border-radius:20px">{{ optional($s->product)->category ?? '—' }}</span></td>
          <td><span style="font-size:14px;font-weight:600;color:{{ $status === 'critical' ? 'var(--peach-dark)' : ($status === 'low' ? '#92400e' : 'var(--text)') }}">{{ $s->quantity }}</span></td>
          <td style="font-size:12px;color:var(--text3)">{{ $s->min_threshold }}</td>
          <td style="width:120px">
            <div style="display:flex;align-items:center;gap:6px">
              <div style="flex:1;height:5px;background:var(--bg);border-radius:5px;overflow:hidden">
                <div style="width:{{ $pct }}%;height:100%;border-radius:5px;background:{{ $barColor }};transition:width 1s ease"></div>
              </div>
              <span style="font-size:10px;color:var(--text3);width:28px">{{ $pct }}%</span>
            </div>
          </td>
          <td>
            @if($s->expiry_date)
              @php $days = \Carbon\Carbon::parse($s->expiry_date)->diffInDays(now(), false) @endphp
              @if($days > 0)<span class="ms-pill pill-cancelled">Expiré</span>
              @elseif(abs($days) <= 7)<span class="ms-pill pill-pending">{{ abs($days) }}j restants</span>
              @elseif(abs($days) <= 30)<span style="font-size:11px;color:#92400e">{{ abs($days) }}j</span>
              @else<span style="font-size:11px;color:var(--text3)">{{ \Carbon\Carbon::parse($s->expiry_date)->format('d/m/Y') }}</span>
              @endif
            @else <span style="color:var(--text3);font-size:12px">—</span>
            @endif
          </td>
          <td style="font-size:12px;color:var(--text3)">{{ $s->location ?? '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:48px;color:var(--text3)"><i class="fa-solid fa-box-open" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.4"></i>Aucun stock trouvé</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('scripts')
<script>
function searchTable(q,id){ q=q.toLowerCase(); document.querySelectorAll('#'+id+' tbody tr').forEach(r=>r.style.display=r.dataset.name.includes(q)?'':'none'); }
function filterStatus(v){ document.querySelectorAll('#stockTable tbody tr').forEach(r=>r.style.display=(!v||r.dataset.status===v)?'':'none'); }
document.querySelectorAll('#stockTable tbody tr').forEach((r,i)=>r.style.animation=`fadeUp 0.3s ease ${0.04*i+0.3}s both`);
</script>
@endsection