@extends('layouts.admin')
@section('title','Commandes')
@section('page-title','Gestion des commandes')
@section('page-subtitle')
Suivi du workflow de validation des commandes
@endsection

@section('content')
<div class="page-header">
  <div><h2>Commandes</h2><p>{{ count($orders) }} commandes au total</p></div>
  <div class="d-flex gap-2">
    <a href="#" class="btn-ms-secondary"><i class="fa-solid fa-file-export"></i> Exporter</a>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-2">
    <div class="kpi-card" style="animation-delay:0.05s">
      <div class="kpi-blob" style="background:var(--blue)"></div>
      <div class="kpi-icon" style="background:var(--blue-soft);color:var(--blue-dark)"><i class="fa-solid fa-cart-shopping"></i></div>
      <div class="kpi-label">Total</div>
      <div class="kpi-value" style="font-size:22px">{{ count($orders) }}</div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="kpi-card" style="animation-delay:0.08s">
      <div class="kpi-blob" style="background:#f59e0b"></div>
      <div class="kpi-icon" style="background:#fef3c7;color:#92400e"><i class="fa-solid fa-clock"></i></div>
      <div class="kpi-label">En attente</div>
      <div class="kpi-value" style="font-size:22px">{{ $orders->where('status','pending')->count() }}</div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="kpi-card" style="animation-delay:0.11s">
      <div class="kpi-blob" style="background:var(--lavender-dark)"></div>
      <div class="kpi-icon" style="background:var(--lavender);color:var(--lavender-dark)"><i class="fa-solid fa-shield-halved"></i></div>
      <div class="kpi-label">À valider</div>
      <div class="kpi-value" style="font-size:22px">{{ $orders->where('status','validated_chief')->count() }}</div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="kpi-card" style="animation-delay:0.14s">
      <div class="kpi-blob" style="background:var(--blue)"></div>
      <div class="kpi-icon" style="background:var(--blue-soft);color:var(--blue-dark)"><i class="fa-solid fa-truck"></i></div>
      <div class="kpi-label">Expédiées</div>
      <div class="kpi-value" style="font-size:22px">{{ $orders->where('status','shipped')->count() }}</div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="kpi-card" style="animation-delay:0.17s">
      <div class="kpi-blob" style="background:var(--mint-dark)"></div>
      <div class="kpi-icon" style="background:var(--mint);color:var(--mint-dark)"><i class="fa-solid fa-circle-check"></i></div>
      <div class="kpi-label">Livrées</div>
      <div class="kpi-value" style="font-size:22px">{{ $orders->where('status','delivered')->count() }}</div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="kpi-card" style="animation-delay:0.20s">
      <div class="kpi-blob" style="background:var(--peach-dark)"></div>
      <div class="kpi-icon" style="background:var(--peach);color:var(--peach-dark)"><i class="fa-solid fa-ban"></i></div>
      <div class="kpi-label">Annulées</div>
      <div class="kpi-value" style="font-size:22px">{{ $orders->where('status','cancelled')->count() }}</div>
    </div>
  </div>
</div>

<div class="ms-card" style="animation-delay:0.25s">
  <div class="ms-card-header">
    <span class="ms-card-title"><i class="fa-solid fa-cart-shopping" style="color:var(--blue);margin-right:8px"></i>Liste des commandes</span>
    <div class="d-flex gap-2">
      <select class="ms-input ms-select" style="width:170px;padding:7px 36px 7px 12px" onchange="filterStatus(this.value)">
        <option value="">Tous les statuts</option>
        <option value="pending">En attente</option>
        <option value="validated_chief">Validé chef</option>
        <option value="validated_admin">Validé admin</option>
        <option value="confirmed">Confirmé</option>
        <option value="shipped">Expédié</option>
        <option value="delivered">Livré</option>
        <option value="cancelled">Annulé</option>
      </select>
      <div class="search-box" style="width:200px">
        <i class="fa-solid fa-magnifying-glass" style="color:var(--text3);font-size:12px"></i>
        <input type="text" placeholder="Réf., hôpital..." oninput="searchTable(this.value,'ordTable')"/>
      </div>
    </div>
  </div>
  <div class="ms-card-body" style="padding:0">
    <table class="ms-table" id="ordTable">
      <thead>
        <tr><th>Référence</th><th>Hôpital</th><th>Fournisseur</th><th>Montant HT</th><th>Statut</th><th>Date commande</th><th style="text-align:right">Actions</th></tr>
      </thead>
      <tbody>
        @forelse($orders as $o)
        <tr data-name="{{ strtolower($o->reference.' '.optional($o->hospital)->name) }}" data-status="{{ $o->status }}">
          <td><span style="font-family:monospace;font-size:12px;color:var(--blue-dark);font-weight:500">{{ $o->reference }}</span></td>
          <td style="font-size:13px;color:var(--text2)">{{ optional($o->hospital)->name ?? '—' }}</td>
          <td style="font-size:12px;color:var(--text3)">{{ optional($o->supplier)->name ?? '—' }}</td>
          <td><strong>{{ number_format($o->total, 2) }} MAD</strong></td>
          <td>
            @php
              $statusMap = [
                'pending'         => ['pill-pending',  'En attente'],
                'validated_chief' => ['pill-chef',     'Validé chef'],
                'validated_admin' => ['pill-admin',    'Validé admin'],
                'confirmed'       => ['pill-confirmed','Confirmé'],
                'shipped'         => ['pill-shipped',  'Expédié'],
                'delivered'       => ['pill-delivered','Livré'],
                'cancelled'       => ['pill-cancelled','Annulé'],
              ];
            @endphp
            @if(isset($statusMap[$o->status]))
              <span class="ms-pill {{ $statusMap[$o->status][0] }}">{{ $statusMap[$o->status][1] }}</span>
            @else
              <span class="ms-pill">{{ $o->status }}</span>
            @endif
          </td>
          <td style="font-size:12px;color:var(--text3)">{{ $o->created_at->format('d/m/Y H:i') }}</td>
          <td>
            <div class="d-flex justify-content-end gap-1">
              <a href="{{ route('admin.orders.show', $o->id) }}" class="icon-btn" style="width:30px;height:30px" title="Voir">
                <i class="fa-solid fa-eye" style="font-size:12px"></i>
              </a>
              @if($o->status === 'validated_chief')
              <form action="{{ route('admin.orders.validate', $o->id) }}" method="POST" style="display:inline">
                @csrf @method('PUT')
                <button type="submit" class="icon-btn" style="width:30px;height:30px;background:var(--mint);border-color:rgba(60,191,153,0.2);color:var(--mint-dark)" title="Valider">
                  <i class="fa-solid fa-check" style="font-size:12px"></i>
                </button>
              </form>
              @endif
              <a href="{{ route('admin.orders.invoice', $o->id) }}" class="icon-btn" style="width:30px;height:30px" title="Facture">
                <i class="fa-solid fa-file-pdf" style="font-size:12px"></i>
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:48px;color:var(--text3)">
            <i class="fa-solid fa-cart-arrow-down" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.4"></i>
            Aucune commande trouvée
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
    @if(method_exists($orders,'links'))
    <div style="padding:16px 22px;border-top:1px solid var(--border);display:flex;justify-content:flex-end">
      {{ $orders->links() }}
    </div>
    @endif
  </div>
</div>

<style>
.pill-pending   { background:#fff3cd;color:#856404; }
.pill-chef      { background:var(--lavender);color:var(--lavender-dark); }
.pill-admin     { background:var(--blue-soft);color:var(--blue-dark); }
.pill-confirmed { background:#e0f2fe;color:#0369a1; }
.pill-shipped   { background:#ede9fe;color:#6d28d9; }
.pill-delivered { background:var(--mint);color:var(--mint-dark); }
.pill-cancelled { background:var(--peach);color:var(--peach-dark); }
</style>

@endsection

@section('scripts')
<script>
function searchTable(q,id){
  q=q.toLowerCase();
  document.querySelectorAll('#'+id+' tbody tr').forEach(r=>r.style.display=r.dataset.name.includes(q)?'':'none');
}
function filterStatus(v){
  document.querySelectorAll('#ordTable tbody tr').forEach(r=>r.style.display=(!v||r.dataset.status===v)?'':'none');
}
document.querySelectorAll('#ordTable tbody tr').forEach((r,i)=>r.style.animation=`fadeUp 0.3s ease ${0.04*i+0.3}s both`);
</script>
@endsection