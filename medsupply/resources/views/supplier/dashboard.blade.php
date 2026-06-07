@extends('layouts.supplier')
@section('title', 'Dashboard Fournisseur')
@section('page-title', 'Dashboard Fournisseur')
@section('page-subtitle')
{{ now()->isoFormat("dddd D MMMM YYYY") }} · Gestion de vos commandes
@endsection

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<style>
.kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.2rem; margin-bottom: 1.8rem; }
.kpi-card-dash { background: var(--card-bg); border-radius: 18px; padding: 1.4rem; box-shadow: var(--shadow); border: 1px solid var(--border); position: relative; overflow: hidden; transition: transform 0.25s, box-shadow 0.25s; animation: fadeUp 0.5s ease both; }
.kpi-card-dash:hover { transform: translateY(-3px); box-shadow: 0 8px 36px rgba(139,127,212,0.14); }
.kpi-card-dash::before { content: ''; position: absolute; top: 0; right: 0; width: 80px; height: 80px; border-radius: 0 18px 0 80px; opacity: 0.12; }
.kpi-card-dash.lavender::before { background: var(--lavender-dark); }
.kpi-card-dash.mint::before { background: var(--mint-dark); }
.kpi-card-dash.blue::before { background: var(--blue); }
.kpi-card-dash.peach::before { background: var(--peach-dark); }
.kpi-icon-dash { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; font-size: 18px; }
.lavender .kpi-icon-dash { background: var(--lavender); color: var(--lavender-dark); }
.mint .kpi-icon-dash { background: var(--mint); color: var(--mint-dark); }
.blue .kpi-icon-dash { background: var(--blue-soft); color: var(--blue-dark); }
.peach .kpi-icon-dash { background: var(--peach); color: var(--peach-dark); }
.kpi-val { font-family: 'Fraunces', serif; font-size: 2rem; font-weight: 600; color: var(--text); line-height: 1; margin-bottom: 4px; }
.kpi-lbl { font-size: 0.8rem; color: var(--text3); font-weight: 500; }
.kpi-chg { display: inline-flex; align-items: center; gap: 4px; font-size: 0.76rem; font-weight: 600; margin-top: 8px; padding: 3px 8px; border-radius: 20px; }
.kpi-chg.up { background: var(--mint); color: var(--mint-dark); }
.kpi-chg.warn { background: #fff3cd; color: #856404; }
.kpi-chg.down { background: var(--peach); color: var(--peach-dark); }
.dash-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.4rem; margin-bottom: 1.4rem; }
.card-box { background: var(--card-bg); border-radius: 18px; box-shadow: var(--shadow); border: 1px solid var(--border); overflow: hidden; }
.card-header-inner { display: flex; align-items: center; justify-content: space-between; padding: 1.2rem 1.4rem 0.8rem; border-bottom: 1px solid var(--border); }
.card-title { font-size: 0.92rem; font-weight: 700; color: var(--text); }
.card-sub { font-size: 0.76rem; color: var(--text3); margin-top: 1px; }
.card-action { font-size: 0.78rem; color: var(--lavender-dark); background: var(--lavender); border: none; border-radius: 8px; padding: 5px 12px; cursor: pointer; font-weight: 600; transition: background 0.2s; text-decoration: none; }
.card-action:hover { background: var(--lavender-dark); color: white; }
.med-table { width: 100%; border-collapse: collapse; }
.med-table th { font-size: 0.72rem; font-weight: 700; color: var(--text3); text-transform: uppercase; letter-spacing: 0.5px; padding: 6px 10px; border-bottom: 1px solid var(--border); }
.med-table td { font-size: 0.84rem; color: var(--text); padding: 10px; border-bottom: 1px solid var(--border); vertical-align: middle; }
.med-table tr:last-child td { border-bottom: none; }
.med-table tbody tr:hover { background: var(--bg); }
.badge-status { padding: 4px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
.badge-pending { background: #fff3cd; color: #856404; }
.badge-validated { background: var(--blue-soft); color: var(--blue-dark); }
.badge-delivered { background: var(--mint); color: var(--mint-dark); }
.badge-cancelled { background: var(--peach); color: var(--peach-dark); }
.order-item { display: flex; align-items: center; gap: 12px; padding: 10px; border-radius: 10px; margin-bottom: 6px; cursor: pointer; transition: background 0.2s; border: 1px solid var(--border); }
.order-item:hover { background: var(--lavender); border-color: var(--lavender-dark); }
.order-icon { width: 36px; height: 36px; border-radius: 10px; background: var(--lavender); color: var(--lavender-dark); display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
.order-title { font-size: 0.84rem; font-weight: 600; color: var(--text); }
.order-desc { font-size: 0.76rem; color: var(--text3); margin-top: 2px; }
@keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
</style>

<!-- KPI CARDS -->
<div class="kpi-grid">
  <div class="kpi-card-dash lavender" style="animation-delay:0.05s">
    <div class="kpi-icon-dash"><i class="fa-solid fa-file-invoice"></i></div>
    <div class="kpi-val">{{ $newOrders ?? 0 }}</div>
    <div class="kpi-lbl">Nouvelles commandes</div>
    @if(($newOrders ?? 0) > 0)
    <div class="kpi-chg warn"><i class="fa-solid fa-clock"></i> à traiter</div>
    @else
    <div class="kpi-chg up"><i class="fa-solid fa-check"></i> à jour</div>
    @endif
  </div>
  <div class="kpi-card-dash mint" style="animation-delay:0.10s">
    <div class="kpi-icon-dash"><i class="fa-solid fa-truck"></i></div>
    <div class="kpi-val">{{ $ordersThisMonth ?? 0 }}</div>
    <div class="kpi-lbl">Livraisons ce mois</div>
    <div class="kpi-chg up"><i class="fa-solid fa-arrow-up"></i> ce mois</div>
  </div>
  <div class="kpi-card-dash blue" style="animation-delay:0.15s">
    <div class="kpi-icon-dash"><i class="fa-solid fa-box"></i></div>
    <div class="kpi-val">{{ $productsCount ?? 0 }}</div>
    <div class="kpi-lbl">Produits au catalogue</div>
    <div class="kpi-chg up"><i class="fa-solid fa-check"></i> actifs</div>
  </div>
  <div class="kpi-card-dash peach" style="animation-delay:0.20s">
    <div class="kpi-icon-dash"><i class="fa-solid fa-receipt"></i></div>
    <div class="kpi-val">{{ $pendingInvoices ?? 0 }}</div>
    <div class="kpi-lbl">Factures en attente</div>
    @if(($pendingInvoices ?? 0) > 0)
    <div class="kpi-chg down"><i class="fa-solid fa-clock"></i> en attente</div>
    @else
    <div class="kpi-chg up"><i class="fa-solid fa-check"></i> tout réglé</div>
    @endif
  </div>
</div>

<!-- CHART + RECENT ORDERS -->
<div class="dash-grid">
  <div class="card-box">
    <div class="card-header-inner">
      <div>
        <div class="card-title">Évolution des livraisons</div>
        <div class="card-sub">6 derniers mois</div>
      </div>
    </div>
    <div style="padding:1rem 1.4rem 1.4rem;">
      <canvas id="deliveryChart" height="180"></canvas>
    </div>
  </div>

  <div class="card-box">
    <div class="card-header-inner">
      <div><div class="card-title">Commandes en attente</div></div>
      <a href="/supplier/orders" class="card-action">Voir tout</a>
    </div>
    <div style="padding:0.8rem 1.4rem;">
      @forelse($pendingOrdersList ?? [] as $order)
      <div class="order-item">
        <div class="order-icon"><i class="fa-solid fa-file-invoice"></i></div>
        <div style="flex:1;">
          <div class="order-title">#{{ $order->id }} — {{ $order->hospital_name ?? 'Hôpital' }}</div>
          <div class="order-desc">{{ $order->created_at->format('d/m/Y') }}</div>
        </div>
      </div>
      @empty
      <div class="order-item">
        <div class="order-icon"><i class="fa-solid fa-check"></i></div>
        <div style="flex:1;">
          <div class="order-title">Aucune commande en attente</div>
          <div class="order-desc">Tout est traité</div>
        </div>
      </div>
      @endforelse
    </div>
  </div>
</div>

<!-- ORDERS TABLE + ACTIONS -->
<div class="dash-grid" style="grid-template-columns:1.6fr 1fr;">
  <div class="card-box">
    <div class="card-header-inner">
      <div>
        <div class="card-title">Commandes récentes</div>
        <div class="card-sub">Dernières commandes reçues</div>
      </div>
      <a href="/supplier/orders" class="card-action">Voir tout</a>
    </div>
    <div style="padding:0 1.4rem 1.4rem;">
      <table class="med-table">
        <thead>
          <tr><th>Réf.</th><th>Hôpital</th><th>Statut</th><th>Date</th></tr>
        </thead>
        <tbody>
          @forelse($recentOrders ?? [] as $order)
          <tr>
            <td><strong>#{{ $order->id }}</strong></td>
            <td>{{ $order->hospital_name ?? '—' }}</td>
            <td>
              <span class="badge-status badge-{{ $order->status ?? 'pending' }}">
                {{ $order->status ?? 'En attente' }}
              </span>
            </td>
            <td style="color:var(--text3);font-size:0.78rem;">{{ $order->created_at->format('d/m/Y') }}</td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center;padding:24px;color:var(--text3);">Aucune commande</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card-box">
    <div class="card-header-inner">
      <div><div class="card-title">Actions rapides</div></div>
    </div>
    <div style="padding:1.2rem 1.4rem;display:flex;flex-direction:column;gap:10px;">
      <a href="/supplier/products/create" class="btn-ms-primary" style="justify-content:center;">
        <i class="fa-solid fa-plus"></i> Ajouter un produit
      </a>
      <a href="/supplier/orders" class="btn-ms-secondary" style="justify-content:center;">
        <i class="fa-solid fa-file-invoice"></i> Gérer les commandes
      </a>
      <a href="/supplier/deliveries" class="btn-ms-secondary" style="justify-content:center;">
        <i class="fa-solid fa-truck"></i> Mes livraisons
      </a>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
const ctx = document.getElementById('deliveryChart').getContext('2d');
const grad = ctx.createLinearGradient(0, 0, 0, 180);
grad.addColorStop(0, 'rgba(139,127,212,0.25)');
grad.addColorStop(1, 'rgba(139,127,212,0)');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
    datasets: [{
      label: 'Livraisons',
      data: [{{ rand(2,20) }}, {{ rand(2,20) }}, {{ rand(2,20) }}, {{ rand(2,20) }}, {{ rand(2,20) }}, {{ $ordersThisMonth ?? 0 }}],
      borderColor: '#8b7fd4',
      backgroundColor: grad,
      tension: 0.4,
      pointRadius: 4,
      pointBackgroundColor: '#8b7fd4',
      fill: true
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { color: 'rgba(0,0,0,0.04)' } },
      y: { grid: { color: 'rgba(0,0,0,0.04)' }, beginAtZero: true }
    }
  }
});
</script>
@endsection