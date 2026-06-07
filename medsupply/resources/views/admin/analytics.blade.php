@extends('layouts.admin')
@section('title', 'Analytics')
@section('page-title', 'Analytics')
@section('page-subtitle', 'MedSupply · Tableau de bord analytique')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>

@if(session('success'))
<div style="background:var(--mint);color:var(--mint-dark);padding:10px 16px;border-radius:11px;margin-bottom:1rem;font-size:0.84rem;font-weight:600;">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

<div class="page-header">
    <div>
        <h2>Analytics</h2>
        <p>Vue d'ensemble en temps réel de la plateforme</p>
    </div>
    {{-- Sélecteur période pour le graphique --}}
    <div style="display:flex;gap:6px;">
        <button onclick="switchPeriod('monthly')" id="btn-monthly" class="btn-ms-primary" style="font-size:0.78rem;padding:7px 14px;">Mensuel</button>
        <button onclick="switchPeriod('quarterly')" id="btn-quarterly" class="btn-ms-secondary" style="font-size:0.78rem;padding:7px 14px;">Trimestriel</button>
        <button onclick="switchPeriod('yearly')" id="btn-yearly" class="btn-ms-secondary" style="font-size:0.78rem;padding:7px 14px;">Annuel</button>
    </div>
</div>

{{-- KPIs --}}
<div style="display:grid;grid-template-columns:repeat(6,1fr);gap:1rem;margin-bottom:1.4rem;">
    <div class="kpi-card" style="animation-delay:0.05s">
        <div class="kpi-blob" style="background:var(--blue)"></div>
        <div class="kpi-icon" style="background:var(--blue-soft);color:var(--blue-dark)"><i class="fa-solid fa-file-invoice"></i></div>
        <div class="kpi-label">Total commandes</div>
        <div class="kpi-value">{{ $totalOrders }}</div>
    </div>
    <div class="kpi-card" style="animation-delay:0.08s">
        <div class="kpi-blob" style="background:var(--mint-dark)"></div>
        <div class="kpi-icon" style="background:var(--mint);color:var(--mint-dark)"><i class="fa-solid fa-money-bill-wave"></i></div>
        <div class="kpi-label">Dépenses ce mois</div>
        <div class="kpi-value" style="font-size:1.3rem;">{{ number_format($depensesMois, 0) }}<span style="font-size:0.7rem;"> DH</span></div>
    </div>
    <div class="kpi-card" style="animation-delay:0.11s">
        <div class="kpi-blob" style="background:var(--lavender-dark)"></div>
        <div class="kpi-icon" style="background:var(--lavender);color:var(--lavender-dark)"><i class="fa-solid fa-spinner"></i></div>
        <div class="kpi-label">Commandes actives</div>
        <div class="kpi-value">{{ $commandesActives }}</div>
    </div>
    <div class="kpi-card" style="animation-delay:0.14s">
        <div class="kpi-blob" style="background:var(--peach-dark)"></div>
        <div class="kpi-icon" style="background:var(--peach);color:var(--peach-dark)"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div class="kpi-label">Fournisseurs non vérifiés</div>
        <div class="kpi-value">{{ $alertes }}</div>
    </div>
    <div class="kpi-card" style="animation-delay:0.17s">
        <div class="kpi-blob" style="background:var(--blue)"></div>
        <div class="kpi-icon" style="background:var(--blue-soft);color:var(--blue-dark)"><i class="fa-solid fa-hospital"></i></div>
        <div class="kpi-label">Hôpitaux</div>
        <div class="kpi-value">{{ $totalHospitals }}</div>
    </div>
    <div class="kpi-card" style="animation-delay:0.20s">
        <div class="kpi-blob" style="background:var(--mint-dark)"></div>
        <div class="kpi-icon" style="background:var(--mint);color:var(--mint-dark)"><i class="fa-solid fa-truck-medical"></i></div>
        <div class="kpi-label">Fournisseurs</div>
        <div class="kpi-value">{{ $totalSuppliers }}</div>
    </div>
</div>

{{-- Graphique dépenses + Statuts --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.4rem;margin-bottom:1.4rem;">
    <div class="ms-card">
        <div class="ms-card-header">
            <span class="ms-card-title"><i class="fa-solid fa-chart-line" style="color:var(--blue);margin-right:8px;"></i>Évolution des dépenses</span>
        </div>
        <div class="ms-card-body">
            <canvas id="depensesChart" height="120"></canvas>
        </div>
    </div>
    <div class="ms-card">
        <div class="ms-card-header">
            <span class="ms-card-title"><i class="fa-solid fa-chart-pie" style="color:var(--lavender-dark);margin-right:8px;"></i>Statuts des commandes</span>
        </div>
        <div class="ms-card-body" style="display:flex;align-items:center;justify-content:center;">
            <canvas id="statusChart" height="200"></canvas>
        </div>
    </div>
</div>

{{-- Top produits + Catégories --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.4rem;margin-bottom:1.4rem;">
    <div class="ms-card">
        <div class="ms-card-header">
            <span class="ms-card-title"><i class="fa-solid fa-ranking-star" style="color:var(--blue);margin-right:8px;"></i>Produits les plus commandés</span>
        </div>
        <div class="ms-card-body" style="padding:0;">
            <table class="ms-table">
                <thead><tr><th>#</th><th>Produit</th><th style="text-align:center;">Qté</th><th style="text-align:right;">Revenus</th></tr></thead>
                <tbody>
                    @forelse($topProducts as $i => $item)
                    <tr>
                        <td style="color:var(--text3);font-weight:700;">{{ $i + 1 }}</td>
                        <td>
                            <div style="font-weight:600;font-size:0.84rem;">{{ $item->product->name ?? '—' }}</div>
                            <div style="font-size:0.72rem;color:var(--text3);">{{ ucfirst($item->product->category ?? '') }}</div>
                        </td>
                        <td style="text-align:center;">
                            <span style="background:var(--blue-soft);color:var(--blue-dark);padding:3px 9px;border-radius:20px;font-size:0.78rem;font-weight:700;">{{ $item->total_qty }}</span>
                        </td>
                        <td style="text-align:right;font-weight:700;color:var(--mint-dark);">{{ number_format($item->total_revenue, 0) }} DH</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--text3);">Aucune donnée</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="ms-card">
        <div class="ms-card-header">
            <span class="ms-card-title"><i class="fa-solid fa-layer-group" style="color:var(--lavender-dark);margin-right:8px;"></i>Consommation par catégorie</span>
        </div>
        <div class="ms-card-body">
            <canvas id="categoryChart" height="200"></canvas>
            <div style="margin-top:1rem;">
                @foreach($byCategory as $cat)
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--blue);"></div>
                        <span style="font-size:0.82rem;color:var(--text);">{{ ucfirst($cat->category) }}</span>
                    </div>
                    <span style="font-size:0.82rem;font-weight:700;color:var(--text);">{{ number_format($cat->total_revenue, 0) }} DH</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Analyse fournisseurs --}}
<div class="ms-card" style="margin-bottom:1.4rem;">
    <div class="ms-card-header">
        <span class="ms-card-title"><i class="fa-solid fa-scale-balanced" style="color:var(--blue);margin-right:8px;"></i>Analyse comparative des fournisseurs</span>
    </div>
    <div class="ms-card-body" style="padding:0;">
        <table class="ms-table">
            <thead>
                <tr>
                    <th>Fournisseur</th>
                    <th style="text-align:center;">Commandes</th>
                    <th style="text-align:right;">Chiffre d'affaires</th>
                    <th style="text-align:center;">Taux livraison</th>
                    <th style="text-align:center;">Délai moyen</th>
                    <th style="text-align:center;">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($supplierAnalysis as $supplier)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:32px;height:32px;border-radius:9px;background:var(--lavender);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:var(--lavender-dark);">
                                {{ strtoupper(substr($supplier->name, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:0.84rem;">{{ $supplier->name }}</div>
                                <div style="font-size:0.72rem;color:var(--text3);">{{ $supplier->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;font-weight:700;">{{ $supplier->orders_count }}</td>
                    <td style="text-align:right;font-weight:700;color:var(--mint-dark);">{{ number_format($supplier->orders_sum_total ?? 0, 0) }} DH</td>
                    <td style="text-align:center;">
                        @php $rate = $supplier->delivery_rate; @endphp
                        <div style="display:flex;align-items:center;gap:6px;justify-content:center;">
                            <div style="width:60px;height:6px;background:var(--border);border-radius:3px;overflow:hidden;">
                                <div style="width:{{ $rate }}%;height:100%;background:{{ $rate >= 80 ? 'var(--mint-dark)' : ($rate >= 50 ? '#f59e0b' : 'var(--peach-dark)') }};border-radius:3px;"></div>
                            </div>
                            <span style="font-size:0.78rem;font-weight:700;">{{ $rate }}%</span>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <span style="font-size:0.82rem;color:var(--text2);">{{ $supplier->avg_delay }} j</span>
                    </td>
                    <td style="text-align:center;">
                        @if($supplier->verified)
                        <span class="ms-pill" style="background:var(--mint);color:var(--mint-dark);">Vérifié</span>
                        @else
                        <span class="ms-pill" style="background:var(--peach);color:var(--peach-dark);">En attente</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--text3);">Aucun fournisseur</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Widget IA proactif --}}
<div class="ms-card" style="margin-bottom:1.4rem;">
    <div class="ms-card-header">
        <span class="ms-card-title"><i class="fa-solid fa-robot" style="color:var(--blue);margin-right:8px;"></i>Suggestions IA proactives</span>
        <button onclick="loadAISuggestions()" id="aiRefreshBtn" class="btn-ms-secondary" style="font-size:0.78rem;padding:6px 14px;">
            <i class="fa-solid fa-rotate"></i> Actualiser
        </button>
    </div>
    <div class="ms-card-body" id="aiSuggestions">
        <div style="text-align:center;padding:1.5rem;color:var(--text3);">
            <i class="fa-solid fa-robot" style="font-size:28px;display:block;margin-bottom:10px;opacity:0.4;"></i>
            <p style="font-size:0.84rem;">Cliquez sur "Actualiser" pour obtenir des suggestions IA basées sur vos données.</p>
            <button onclick="loadAISuggestions()" class="btn-ms-primary" style="margin-top:10px;font-size:0.82rem;">
                <i class="fa-solid fa-magic-wand-sparkles"></i> Générer les suggestions
            </button>
        </div>
    </div>
</div>

{{-- Top hôpitaux --}}
<div class="ms-card">
    <div class="ms-card-header">
        <span class="ms-card-title"><i class="fa-solid fa-hospital" style="color:var(--blue);margin-right:8px;"></i>Top hôpitaux commandeurs</span>
    </div>
    <div class="ms-card-body" style="padding:0;">
        <table class="ms-table">
            <thead><tr><th>#</th><th>Hôpital</th><th style="text-align:center;">Commandes</th><th style="text-align:right;">Total dépensé</th></tr></thead>
            <tbody>
                @forelse($topHospitals as $i => $h)
                <tr>
                    <td style="color:var(--text3);font-weight:700;">{{ $i + 1 }}</td>
                    <td style="font-weight:600;">{{ $h->name }}</td>
                    <td style="text-align:center;"><span style="background:var(--blue-soft);color:var(--blue-dark);padding:3px 9px;border-radius:20px;font-size:0.78rem;font-weight:700;">{{ $h->orders_count }}</span></td>
                    <td style="text-align:right;font-weight:700;color:var(--mint-dark);">{{ number_format($h->orders_sum_total ?? 0, 0) }} DH</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--text3);">Aucune donnée</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
// ── Données PHP → JS ─────────────────────────────────────────
const monthly    = { labels: @json($labels),  data: @json($depensesMensuelles) };
const quarterly  = { labels: @json($labelsT), data: @json($depensesTrimestrielles) };
const yearly     = { labels: @json($labelsA), data: @json($depensesAnnuelles) };

const statusData  = @json($ordersByStatus);
const categoryData = @json($byCategory);

// ── Graphique dépenses ────────────────────────────────────────
const ctxD = document.getElementById('depensesChart').getContext('2d');
const grad = ctxD.createLinearGradient(0, 0, 0, 250);
grad.addColorStop(0, 'rgba(91,155,213,0.3)');
grad.addColorStop(1, 'rgba(91,155,213,0)');

let depensesChart = new Chart(ctxD, {
    type: 'line',
    data: {
        labels: monthly.labels,
        datasets: [{
            label: 'Dépenses (DH)',
            data: monthly.data,
            borderColor: '#5b9bd5',
            backgroundColor: grad,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#5b9bd5',
            fill: true,
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

function switchPeriod(period) {
    const map = { monthly, quarterly, yearly };
    const d   = map[period];
    depensesChart.data.labels = d.labels;
    depensesChart.data.datasets[0].data = d.data;
    depensesChart.update();

    ['monthly','quarterly','yearly'].forEach(p => {
        const btn = document.getElementById('btn-' + p);
        btn.className = p === period ? 'btn-ms-primary' : 'btn-ms-secondary';
        btn.style.fontSize = '0.78rem';
        btn.style.padding  = '7px 14px';
    });
}

// ── Graphique statuts ─────────────────────────────────────────
const statusLabels = { pending:'En attente', validated:'Validée', confirmed:'Confirmée', shipped:'Expédiée', delivered:'Livrée', cancelled:'Annulée' };
const statusColors = { pending:'#f59e0b', validated:'#5b9bd5', confirmed:'#8b7fd4', shipped:'#3a7bbf', delivered:'#3cbf99', cancelled:'#e07050' };

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(statusData).map(k => statusLabels[k] ?? k),
        datasets: [{
            data: Object.values(statusData),
            backgroundColor: Object.keys(statusData).map(k => statusColors[k] ?? '#ccc'),
            borderWidth: 2,
            borderColor: 'var(--card-bg)',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12 } }
        }
    }
});

// ── Graphique catégories ──────────────────────────────────────
const catColors = ['#5b9bd5','#3cbf99','#8b7fd4','#e07050','#f59e0b','#64748b'];
new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: {
        labels: categoryData.map(c => c.category ? c.category.charAt(0).toUpperCase() + c.category.slice(1) : '—'),
        datasets: [{
            label: 'Revenus (DH)',
            data: categoryData.map(c => c.total_revenue),
            backgroundColor: catColors,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false } },
            y: { grid: { color: 'rgba(0,0,0,0.04)' }, beginAtZero: true }
        }
    }
});

// ── Widget IA proactif ────────────────────────────────────────
async function loadAISuggestions() {
    const btn = document.getElementById('aiRefreshBtn');
    const container = document.getElementById('aiSuggestions');

    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Analyse...';
    btn.disabled = true;

    container.innerHTML = `<div style="text-align:center;padding:1.5rem;color:var(--text3);">
        <i class="fa-solid fa-spinner fa-spin" style="font-size:24px;display:block;margin-bottom:10px;color:var(--blue);"></i>
        <p style="font-size:0.84rem;">L'IA analyse vos données...</p>
    </div>`;

    const context = {
        totalOrders: {{ $totalOrders }},
        commandesActives: {{ $commandesActives }},
        depensesMois: {{ $depensesMois }},
        alertes: {{ $alertes }},
        topProducts: @json($topProducts->take(3)->map(fn($p) => ['name' => $p->product->name ?? '?', 'qty' => $p->total_qty])),
        byCategory: @json($byCategory->map(fn($c) => ['category' => $c->category, 'revenue' => $c->total_revenue])),
        supplierCount: {{ $totalSuppliers }},
    };

    try {
        const res = await fetch('{{ route("admin.ai.chat") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                message: `Tu es un assistant analytique pour une plateforme de gestion de fournitures médicales (MedSupply).
Voici les données actuelles : ${JSON.stringify(context)}.
Génère 4 suggestions proactives et concrètes pour améliorer la gestion (commandes, stock, fournisseurs, dépenses).
Réponds en JSON avec ce format : [{"icon":"fa-...", "titre":"...", "description":"...", "priorite":"haute|moyenne|faible"}]
Réponds UNIQUEMENT avec le JSON, sans markdown ni texte autour.`
            })
        });

        const data = await res.json();
        let suggestions = [];

        try {
            const text = data.response ?? data.message ?? '';
            suggestions = JSON.parse(text.replace(/```json|```/g, '').trim());
        } catch {
            suggestions = [
                { icon: 'fa-chart-line', titre: 'Optimiser les commandes', description: 'Regroupez les commandes similaires pour réduire les coûts de livraison.', priorite: 'haute' },
                { icon: 'fa-truck', titre: 'Évaluer les fournisseurs', description: 'Comparez régulièrement les délais et tarifs de vos fournisseurs.', priorite: 'moyenne' },
            ];
        }

        const colors = { haute: ['var(--peach)', 'var(--peach-dark)'], moyenne: ['#fff3cd', '#856404'], faible: ['var(--mint)', 'var(--mint-dark)'] };

        container.innerHTML = `<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;">
            ${suggestions.map(s => {
                const [bg, color] = colors[s.priorite] ?? colors['faible'];
                return `<div style="background:var(--bg);border-radius:12px;padding:1rem;border:1px solid var(--border);">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                        <div style="width:36px;height:36px;border-radius:10px;background:${bg};display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid ${s.icon}" style="color:${color};font-size:14px;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.85rem;color:var(--text);">${s.titre}</div>
                            <span style="font-size:0.65rem;background:${bg};color:${color};padding:2px 7px;border-radius:20px;font-weight:700;">${s.priorite}</span>
                        </div>
                    </div>
                    <p style="font-size:0.8rem;color:var(--text2);line-height:1.5;">${s.description}</p>
                </div>`;
            }).join('')}
        </div>`;

    } catch (e) {
        container.innerHTML = `<div style="text-align:center;padding:1rem;color:var(--peach-dark);font-size:0.84rem;">
            <i class="fa-solid fa-triangle-exclamation"></i> Erreur lors de la génération des suggestions.
        </div>`;
    }

    btn.innerHTML = '<i class="fa-solid fa-rotate"></i> Actualiser';
    btn.disabled = false;
}
</script>
@endsection