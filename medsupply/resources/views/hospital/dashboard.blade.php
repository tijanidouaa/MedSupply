@extends('layouts.hospital')
@section('title', 'Dashboard Hôpital')
@section('page-title', 'Dashboard Chef d\'Hôpital')
@section('page-subtitle')
{{ now()->isoFormat("dddd D MMMM YYYY") }} · Gestion de votre hôpital
@endsection

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<style>
.kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.2rem; margin-bottom: 1.8rem; }
.kpi-card-dash { background: var(--card-bg); border-radius: 18px; padding: 1.4rem; box-shadow: var(--shadow); border: 1px solid var(--border); position: relative; overflow: hidden; transition: transform 0.25s, box-shadow 0.25s; animation: fadeUp 0.5s ease both; }
.kpi-card-dash:hover { transform: translateY(-3px); box-shadow: 0 8px 36px rgba(91,155,213,0.14); }
.kpi-card-dash::before { content: ''; position: absolute; top: 0; right: 0; width: 80px; height: 80px; border-radius: 0 18px 0 80px; opacity: 0.12; }
.kpi-card-dash.blue::before   { background: var(--blue); }
.kpi-card-dash.mint::before   { background: var(--mint-dark); }
.kpi-card-dash.lavender::before { background: var(--lavender-dark); }
.kpi-card-dash.peach::before  { background: var(--peach-dark); }
.kpi-icon-dash { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; font-size: 18px; }
.blue .kpi-icon-dash     { background: var(--blue-soft); color: var(--blue-dark); }
.mint .kpi-icon-dash     { background: var(--mint); color: var(--mint-dark); }
.lavender .kpi-icon-dash { background: var(--lavender); color: var(--lavender-dark); }
.peach .kpi-icon-dash    { background: var(--peach); color: var(--peach-dark); }
.kpi-val { font-family: 'Fraunces', serif; font-size: 2rem; font-weight: 600; color: var(--text); line-height: 1; margin-bottom: 4px; }
.kpi-lbl { font-size: 0.8rem; color: var(--text3); font-weight: 500; }
.kpi-chg { display: inline-flex; align-items: center; gap: 4px; font-size: 0.76rem; font-weight: 600; margin-top: 8px; padding: 3px 8px; border-radius: 20px; }
.kpi-chg.up   { background: var(--mint); color: var(--mint-dark); }
.kpi-chg.warn { background: #fff3cd; color: #856404; }
.kpi-chg.down { background: var(--peach); color: var(--peach-dark); }
.dash-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.4rem; margin-bottom: 1.4rem; }
.card-box { background: var(--card-bg); border-radius: 18px; box-shadow: var(--shadow); border: 1px solid var(--border); overflow: hidden; }
.card-header-inner { display: flex; align-items: center; justify-content: space-between; padding: 1.2rem 1.4rem 0.8rem; border-bottom: 1px solid var(--border); }
.card-title { font-size: 0.92rem; font-weight: 700; color: var(--text); }
.card-sub { font-size: 0.76rem; color: var(--text3); margin-top: 1px; }
.card-action { font-size: 0.78rem; color: var(--mint-dark); background: var(--mint); border: none; border-radius: 8px; padding: 5px 12px; cursor: pointer; font-weight: 600; transition: background 0.2s; text-decoration: none; }
.card-action:hover { background: var(--mint-dark); color: white; }
.med-table { width: 100%; border-collapse: collapse; }
.med-table th { font-size: 0.72rem; font-weight: 700; color: var(--text3); text-transform: uppercase; letter-spacing: 0.5px; padding: 6px 10px; border-bottom: 1px solid var(--border); }
.med-table td { font-size: 0.84rem; color: var(--text); padding: 10px; border-bottom: 1px solid var(--border); vertical-align: middle; }
.med-table tr:last-child td { border-bottom: none; }
.med-table tbody tr:hover { background: var(--bg); }
.badge-status { padding: 4px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
.badge-pending   { background: #fff3cd; color: #856404; }
.badge-validated { background: var(--blue-soft); color: var(--blue-dark); }
.badge-confirmed { background: var(--lavender); color: var(--lavender-dark); }
.badge-delivered { background: var(--mint); color: var(--mint-dark); }
.badge-cancelled { background: var(--peach); color: var(--peach-dark); }
.alert-item { display: flex; align-items: flex-start; gap: 12px; padding: 10px; border-radius: 10px; margin-bottom: 6px; transition: background 0.2s; }
.alert-item:hover { background: var(--bg); }
.alert-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; margin-top: 5px; }
.alert-dot.red    { background: #e05d5d; box-shadow: 0 0 0 3px rgba(224,93,93,0.2); }
.alert-dot.orange { background: #f5a623; box-shadow: 0 0 0 3px rgba(245,166,35,0.2); }
.alert-dot.green  { background: var(--mint-dark); box-shadow: 0 0 0 3px rgba(60,191,153,0.2); }
.alert-title { font-size: 0.84rem; font-weight: 600; color: var(--text); }
.alert-desc  { font-size: 0.76rem; color: var(--text3); margin-top: 2px; }
.alert-time  { font-size: 0.72rem; color: var(--text3); margin-left: auto; white-space: nowrap; }
@keyframes fadeUp { from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);} }
</style>

<!-- KPI CARDS -->
<div class="kpi-grid">
  <div class="kpi-card-dash blue" style="animation-delay:0.05s">
    <div class="kpi-icon-dash"><i class="fa-solid fa-file-invoice"></i></div>
    <div class="kpi-val">{{ $ordersCount }}</div>
    <div class="kpi-lbl">Commandes ce mois</div>
    <div class="kpi-chg up"><i class="fa-solid fa-arrow-up"></i> ce mois</div>
  </div>
  <div class="kpi-card-dash mint" style="animation-delay:0.10s">
    <div class="kpi-icon-dash"><i class="fa-solid fa-box-open"></i></div>
    <div class="kpi-val">{{ $stockItems }}</div>
    <div class="kpi-lbl">Produits en stock</div>
    <div class="kpi-chg up"><i class="fa-solid fa-check"></i> disponibles</div>
  </div>
  <div class="kpi-card-dash lavender" style="animation-delay:0.15s">
    <div class="kpi-icon-dash"><i class="fa-solid fa-truck-medical"></i></div>
    <div class="kpi-val">{{ $pendingDeliveries }}</div>
    <div class="kpi-lbl">Livraisons en attente</div>
    @if($pendingDeliveries > 0)
    <div class="kpi-chg warn"><i class="fa-solid fa-clock"></i> en cours</div>
    @else
    <div class="kpi-chg up"><i class="fa-solid fa-check"></i> à jour</div>
    @endif
  </div>
  <div class="kpi-card-dash peach" style="animation-delay:0.20s">
    <div class="kpi-icon-dash"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <div class="kpi-val">{{ $criticalAlerts }}</div>
    <div class="kpi-lbl">Alertes stock critiques</div>
    @if($criticalAlerts > 0)
    <div class="kpi-chg down"><i class="fa-solid fa-arrow-up"></i> urgents</div>
    @else
    <div class="kpi-chg up"><i class="fa-solid fa-check"></i> tout OK</div>
    @endif
  </div>
</div>

<!-- CHART + ALERTS -->
<div class="dash-grid">
  <div class="card-box">
    <div class="card-header-inner">
      <div>
        <div class="card-title">Évolution de mes commandes</div>
        <div class="card-sub">6 derniers mois</div>
      </div>
    </div>
    <div style="padding:1rem 1.4rem 1.4rem;">
      <canvas id="ordersChart" height="180"></canvas>
    </div>
  </div>

  <div class="card-box">
    <div class="card-header-inner">
      <div><div class="card-title">Alertes Stock</div></div>
      <a href="/hospital/alerts" class="card-action">Voir tout</a>
    </div>
    <div style="padding:0.8rem 1.4rem;">
      @forelse($stockAlerts as $alert)
      <div class="alert-item">
        <span class="alert-dot {{ $alert->quantity == 0 ? 'red' : 'orange' }}"></span>
        <div style="flex:1;">
          <div class="alert-title">
            {{ $alert->quantity == 0 ? 'Rupture' : 'Stock bas' }} — {{ $alert->name }}
          </div>
          <div class="alert-desc">Stock: {{ $alert->quantity }} / min: {{ $alert->min_quantity }}</div>
        </div>
      </div>
      @empty
      <div style="text-align:center;padding:20px;color:var(--text3);font-size:0.82rem;">
        <i class="fa-solid fa-check-circle" style="color:var(--mint-dark);font-size:24px;display:block;margin-bottom:8px;"></i>
        Aucune alerte stock
      </div>
      @endforelse
    </div>
  </div>
</div>

<!-- RECENT ORDERS + ACTIONS -->
<div class="dash-grid" style="grid-template-columns:1.6fr 1fr;">
  <div class="card-box">
    <div class="card-header-inner">
      <div>
        <div class="card-title">Mes commandes récentes</div>
        <div class="card-sub">{{ $recentOrders->count() }} dernières commandes</div>
      </div>
      <a href="/hospital/orders" class="card-action">Voir tout</a>
    </div>
    <div style="padding:0 1.4rem 1.4rem;">
      <table class="med-table">
        <thead>
          <tr><th>Réf.</th><th>Fournisseur</th><th>Statut</th><th>Date</th></tr>
        </thead>
        <tbody>
          @forelse($recentOrders as $order)
          <tr>
            <td><strong style="font-size:0.78rem;font-family:monospace;color:var(--blue-dark);">{{ $order->reference }}</strong></td>
            <td style="font-size:0.82rem;">{{ optional($order->supplier)->name ?? '—' }}</td>
            <td>
              <span class="badge-status badge-{{ $order->status }}">
                {{ ['pending'=>'En attente','validated'=>'Validé','confirmed'=>'Confirmé','delivered'=>'Livré','cancelled'=>'Annulé'][$order->status] ?? $order->status }}
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
      <a href="/hospital/orders/create" class="btn-ms-primary" style="justify-content:center;">
        <i class="fa-solid fa-plus"></i> Nouvelle commande
      </a>
      <a href="/hospital/catalog" class="btn-ms-secondary" style="justify-content:center;">
        <i class="fa-solid fa-store"></i> Voir le catalogue
      </a>
      <a href="/hospital/stock" class="btn-ms-secondary" style="justify-content:center;">
        <i class="fa-solid fa-box-open"></i> Gérer le stock
      </a>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
const ctx = document.getElementById('ordersChart').getContext('2d');
const grad = ctx.createLinearGradient(0, 0, 0, 200);
grad.addColorStop(0, 'rgba(60,191,153,0.25)');
grad.addColorStop(1, 'rgba(60,191,153,0)');

// Labels des 6 derniers mois
const months = [];
for (let i = 5; i >= 0; i--) {
    const d = new Date();
    d.setMonth(d.getMonth() - i);
    months.push(d.toLocaleString('fr-FR', { month: 'short' }));
}

new Chart(ctx, {
  type: 'line',
  data: {
    labels: months,
    datasets: [{
      label: 'Commandes',
      data: @json($chartData),
      borderColor: '#3cbf99',
      backgroundColor: grad,
      tension: 0.4,
      pointRadius: 4,
      pointBackgroundColor: '#3cbf99',
      fill: true
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { color: 'rgba(0,0,0,0.04)' } },
      y: { grid: { color: 'rgba(0,0,0,0.04)' }, beginAtZero: true, ticks: { stepSize: 1 } }
    }
  }
});
</script>
@endsection