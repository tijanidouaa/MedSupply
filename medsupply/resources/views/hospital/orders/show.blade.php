@extends('layouts.hospital')
@section('title', 'Détail Commande')
@section('page-title', 'Détail Commande')
@section('page-subtitle', 'MedSupply · {{ $order->reference }}')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $order->reference }}</h2>
        <p>Détails de la commande</p>
    </div>
    <div style="display:flex;gap:10px;">
        <button onclick="printReceipt()" class="btn-ms-primary" style="background:linear-gradient(135deg,var(--mint-dark),#2da882);">
            <i class="fa-solid fa-print"></i> Imprimer le reçu
        </button>
        <a href="{{ route('hospital.orders.tracking', $order->id) }}" class="btn-ms-primary">
            <i class="fa-solid fa-satellite-dish"></i> Suivi du colis
        </a>
        <a href="{{ route('hospital.orders.index') }}" class="btn-ms-secondary">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.4rem;">

    {{-- Informations + Articles --}}
    <div style="display:flex;flex-direction:column;gap:1.2rem;">

        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title"><i class="fa-solid fa-file-invoice" style="color:var(--blue);margin-right:8px;"></i>Informations</span>
            </div>
            <div class="ms-card-body">
                @php
                    $pills  = ['pending'=>'pill-pending','validated'=>'pill-validated','confirmed'=>'pill-confirmed','delivered'=>'pill-delivered','cancelled'=>'pill-cancelled'];
                    $labels = ['pending'=>'En attente','validated'=>'Validée','confirmed'=>'Confirmée','delivered'=>'Livrée','cancelled'=>'Annulée'];
                @endphp
                <div style="display:flex;flex-direction:column;gap:0;">
                    <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
                        <span style="color:var(--text3);font-size:0.82rem;">Référence</span>
                        <span style="font-weight:700;color:var(--blue-dark);">{{ $order->reference }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
                        <span style="color:var(--text3);font-size:0.82rem;">Statut</span>
                        <span class="ms-pill {{ $pills[$order->status] ?? 'pill-pending' }}">{{ $labels[$order->status] ?? $order->status }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
                        <span style="color:var(--text3);font-size:0.82rem;">Fournisseur</span>
                        <span style="font-weight:600;">{{ $order->supplier->name ?? '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
                        <span style="color:var(--text3);font-size:0.82rem;">Hôpital</span>
                        <span style="font-weight:600;">{{ $order->hospital->name ?? '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
                        <span style="color:var(--text3);font-size:0.82rem;">Numéro de tracking</span>
                        <span style="font-weight:600;font-family:monospace;font-size:0.82rem;color:var(--blue-dark);">{{ $order->tracking_number ?? '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:10px 0;">
                        <span style="color:var(--text3);font-size:0.82rem;">Date de création</span>
                        <span style="font-weight:600;">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Articles --}}
        <div class="ms-card">
            <div class="ms-card-header">
                <span class="ms-card-title"><i class="fa-solid fa-box-open" style="color:var(--blue);margin-right:8px;"></i>Articles commandés</span>
                <span style="font-size:0.78rem;color:var(--text3);">{{ $order->items->count() }} article(s)</span>
            </div>
            <div class="ms-card-body" style="padding:0;">
                @if($order->items->count() > 0)
                <table class="ms-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th style="text-align:center;">Qté</th>
                            <th style="text-align:right;">Prix unit.</th>
                            <th style="text-align:right;">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:0.84rem;">{{ $item->product->name ?? '—' }}</div>
                                @if($item->product->reference ?? false)
                                <div style="font-size:0.72rem;color:var(--text3);">Réf: {{ $item->product->reference }}</div>
                                @endif
                            </td>
                            <td style="text-align:center;font-weight:600;">{{ $item->quantity }}</td>
                            <td style="text-align:right;">{{ number_format($item->unit_price, 2) }} DH</td>
                            <td style="text-align:right;font-weight:700;color:var(--lavender-dark);">{{ number_format($item->subtotal, 2) }} DH</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:var(--bg);">
                            <td colspan="3" style="text-align:right;padding:12px;font-weight:600;color:var(--text2);">Total HT</td>
                            <td style="text-align:right;padding:12px;font-weight:700;">{{ number_format($order->total, 2) }} DH</td>
                        </tr>
                        <tr style="background:var(--bg);">
                            <td colspan="3" style="text-align:right;padding:12px;font-weight:600;color:var(--text2);">TVA (20%)</td>
                            <td style="text-align:right;padding:12px;font-weight:700;">{{ number_format($order->total * 0.20, 2) }} DH</td>
                        </tr>
                        <tr style="background:var(--blue-soft);">
                            <td colspan="3" style="text-align:right;padding:12px;font-weight:700;color:var(--blue-dark);">Total TTC</td>
                            <td style="text-align:right;padding:12px;font-weight:700;font-size:1.1rem;color:var(--blue-dark);">{{ number_format($order->total * 1.20, 2) }} DH</td>
                        </tr>
                    </tfoot>
                </table>
                @else
                <div style="text-align:center;padding:2rem;color:var(--text3);">
                    <i class="fa-solid fa-box-open" style="font-size:24px;opacity:0.4;display:block;margin-bottom:8px;"></i>
                    Aucun article enregistré pour cette commande.
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Progression --}}
    <div class="ms-card" style="align-self:start;position:sticky;top:80px;">
        <div class="ms-card-header">
            <span class="ms-card-title"><i class="fa-solid fa-timeline" style="color:var(--blue);margin-right:8px;"></i>Progression</span>
        </div>
        <div class="ms-card-body">
            @php
                $steps = [
                    ['key'=>'pending',   'label'=>'Commande soumise',     'icon'=>'fa-file-invoice'],
                    ['key'=>'validated', 'label'=>'Validée par admin',    'icon'=>'fa-shield-halved'],
                    ['key'=>'confirmed', 'label'=>'Confirmée fournisseur','icon'=>'fa-handshake'],
                    ['key'=>'delivered', 'label'=>'Livrée',               'icon'=>'fa-box-open'],
                ];
                $stepOrder = ['pending'=>1,'validated'=>2,'confirmed'=>3,'delivered'=>4,'cancelled'=>0];
                $current   = $stepOrder[$order->status] ?? 1;
            @endphp
            @foreach($steps as $i => $step)
            <div style="display:flex;gap:12px;align-items:flex-start;margin-bottom:{{ $i < count($steps)-1 ? '0' : '0' }};">
                <div style="display:flex;flex-direction:column;align-items:center;">
                    <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;
                        background:{{ $current >= $i+1 ? 'var(--blue)' : 'var(--bg)' }};
                        color:{{ $current >= $i+1 ? 'white' : 'var(--text3)' }};
                        border:2px solid {{ $current >= $i+1 ? 'var(--blue)' : 'var(--border)' }};">
                        <i class="fa-solid {{ $step['icon'] }}"></i>
                    </div>
                    @if($i < count($steps)-1)
                    <div style="width:2px;height:24px;background:{{ $current > $i+1 ? 'var(--blue)' : 'var(--border)' }};margin:4px 0;"></div>
                    @endif
                </div>
                <div style="padding-bottom:{{ $i < count($steps)-1 ? '0' : '0' }};">
                    <div style="font-size:0.82rem;font-weight:600;color:{{ $current >= $i+1 ? 'var(--text)' : 'var(--text3)' }};">{{ $step['label'] }}</div>
                    @if($current >= $i+1)
                    <div style="font-size:0.72rem;color:var(--text3);">{{ $order->created_at->format('d/m/Y') }}</div>
                    @endif
                    @if($current === $i+1)
                    <span style="display:inline-block;margin-top:3px;font-size:0.65rem;background:var(--blue-soft);color:var(--blue-dark);padding:2px 7px;border-radius:20px;font-weight:700;">Actuel</span>
                    @endif
                </div>
            </div>
            @endforeach

            @if($order->tracking_number)
            <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border);text-align:center;">
                <a href="{{ route('hospital.orders.tracking', $order->id) }}" class="btn-ms-primary" style="font-size:0.8rem;width:100%;justify-content:center;">
                    <i class="fa-solid fa-satellite-dish"></i> Voir le tracking détaillé
                </a>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- Zone d'impression (cachée à l'écran) --}}
<div id="printArea" style="display:none;">
<div style="font-family:'DM Sans',Arial,sans-serif;max-width:700px;margin:0 auto;padding:30px;color:#1e2d45;">

    {{-- En-tête --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:30px;padding-bottom:20px;border-bottom:2px solid #5b9bd5;">
        <div>
            <div style="font-size:24px;font-weight:700;color:#3a7bbf;">🏥 MedSupply</div>
            <div style="font-size:12px;color:#8a9ab5;margin-top:4px;">Plateforme intelligente des achats médicaux</div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:20px;font-weight:700;color:#1e2d45;">BON DE COMMANDE</div>
            <div style="font-size:14px;font-weight:700;color:#3a7bbf;margin-top:4px;">{{ $order->reference }}</div>
            <div style="font-size:11px;color:#8a9ab5;margin-top:2px;">{{ $order->created_at->format('d/m/Y à H:i') }}</div>
        </div>
    </div>

    {{-- Infos hôpital / fournisseur --}}
    <div style="display:flex;gap:30px;margin-bottom:25px;">
        <div style="flex:1;background:#f4f7fb;border-radius:10px;padding:15px;">
            <div style="font-size:10px;font-weight:700;color:#8a9ab5;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Commandé par</div>
            <div style="font-weight:700;font-size:14px;">{{ $order->hospital->name ?? '—' }}</div>
            <div style="font-size:12px;color:#5e7291;margin-top:3px;">{{ auth()->user()->name }}</div>
            <div style="font-size:12px;color:#5e7291;">{{ auth()->user()->email }}</div>
        </div>
        <div style="flex:1;background:#f4f7fb;border-radius:10px;padding:15px;">
            <div style="font-size:10px;font-weight:700;color:#8a9ab5;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Fournisseur</div>
            <div style="font-weight:700;font-size:14px;">{{ $order->supplier->name ?? '—' }}</div>
            @if($order->supplier->email ?? false)
            <div style="font-size:12px;color:#5e7291;margin-top:3px;">{{ $order->supplier->email }}</div>
            @endif
            @if($order->supplier->phone ?? false)
            <div style="font-size:12px;color:#5e7291;">{{ $order->supplier->phone }}</div>
            @endif
        </div>
        <div style="flex:1;background:#f4f7fb;border-radius:10px;padding:15px;">
            <div style="font-size:10px;font-weight:700;color:#8a9ab5;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Statut</div>
            @php $labels = ['pending'=>'En attente','validated'=>'Validée','confirmed'=>'Confirmée','delivered'=>'Livrée','cancelled'=>'Annulée']; @endphp
            <div style="font-weight:700;font-size:14px;">{{ $labels[$order->status] ?? $order->status }}</div>
            @if($order->tracking_number)
            <div style="font-size:11px;color:#5e7291;margin-top:3px;">Tracking : <strong>{{ $order->tracking_number }}</strong></div>
            @endif
        </div>
    </div>

    {{-- Tableau articles --}}
    <table style="width:100%;border-collapse:collapse;margin-bottom:20px;">
        <thead>
            <tr style="background:#3a7bbf;color:white;">
                <th style="padding:10px 12px;text-align:left;font-size:11px;font-weight:700;border-radius:6px 0 0 0;">PRODUIT</th>
                <th style="padding:10px 12px;text-align:center;font-size:11px;font-weight:700;width:70px;">QTÉ</th>
                <th style="padding:10px 12px;text-align:right;font-size:11px;font-weight:700;width:110px;">PRIX UNIT.</th>
                <th style="padding:10px 12px;text-align:right;font-size:11px;font-weight:700;width:110px;border-radius:0 6px 0 0;">SOUS-TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @forelse($order->items as $i => $item)
            <tr style="background:{{ $i % 2 === 0 ? '#ffffff' : '#f8fafd' }};">
                <td style="padding:10px 12px;font-size:13px;font-weight:600;border-bottom:1px solid #e8edf5;">
                    {{ $item->product->name ?? '—' }}
                    @if($item->product->reference ?? false)
                    <div style="font-size:10px;color:#8a9ab5;">Réf: {{ $item->product->reference }}</div>
                    @endif
                </td>
                <td style="padding:10px 12px;text-align:center;font-size:13px;font-weight:700;border-bottom:1px solid #e8edf5;">{{ $item->quantity }}</td>
                <td style="padding:10px 12px;text-align:right;font-size:13px;border-bottom:1px solid #e8edf5;">{{ number_format($item->unit_price, 2) }} DH</td>
                <td style="padding:10px 12px;text-align:right;font-size:13px;font-weight:700;color:#3a7bbf;border-bottom:1px solid #e8edf5;">{{ number_format($item->subtotal, 2) }} DH</td>
            </tr>
            @empty
            <tr><td colspan="4" style="padding:20px;text-align:center;color:#8a9ab5;font-size:13px;">Aucun article</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="padding:8px 12px;text-align:right;font-size:12px;color:#5e7291;font-weight:600;">Total HT</td>
                <td style="padding:8px 12px;text-align:right;font-size:13px;font-weight:700;">{{ number_format($order->total, 2) }} DH</td>
            </tr>
            <tr>
                <td colspan="3" style="padding:8px 12px;text-align:right;font-size:12px;color:#5e7291;font-weight:600;">TVA (20%)</td>
                <td style="padding:8px 12px;text-align:right;font-size:13px;font-weight:700;">{{ number_format($order->total * 0.20, 2) }} DH</td>
            </tr>
            <tr style="background:#ddeeff;">
                <td colspan="3" style="padding:12px;text-align:right;font-size:14px;font-weight:700;color:#3a7bbf;border-radius:0 0 0 6px;">TOTAL TTC</td>
                <td style="padding:12px;text-align:right;font-size:16px;font-weight:700;color:#3a7bbf;border-radius:0 0 6px 0;">{{ number_format($order->total * 1.20, 2) }} DH</td>
            </tr>
        </tfoot>
    </table>

    {{-- Notes --}}
    @if($order->notes ?? false)
    <div style="background:#f4f7fb;border-radius:8px;padding:12px 15px;margin-bottom:20px;">
        <div style="font-size:10px;font-weight:700;color:#8a9ab5;text-transform:uppercase;letter-spacing:1px;margin-bottom:5px;">Notes</div>
        <div style="font-size:12px;color:#5e7291;">{{ $order->notes }}</div>
    </div>
    @endif

    {{-- Pied de page --}}
    <div style="border-top:1px solid #e8edf5;padding-top:15px;display:flex;justify-content:space-between;align-items:center;">
        <div style="font-size:10px;color:#8a9ab5;">Imprimé le {{ now()->format('d/m/Y à H:i') }} · MedSupply</div>
        <div style="font-size:10px;color:#8a9ab5;">Document généré automatiquement — non contractuel</div>
    </div>

</div>
</div>

<style>
.pill-pending   { background:#fff3cd;color:#856404; }
.pill-validated { background:var(--blue-soft);color:var(--blue-dark); }
.pill-confirmed { background:var(--lavender);color:var(--lavender-dark); }
.pill-delivered { background:var(--mint);color:var(--mint-dark); }
.pill-cancelled { background:var(--peach);color:var(--peach-dark); }

@media print {
    body > * { display: none !important; }
    #printArea { display: block !important; }
}
</style>

@section('scripts')
<script>
function printReceipt() {
    const printContent = document.getElementById('printArea').innerHTML;
    const win = window.open('', '_blank', 'width=800,height=900');
    win.document.write(`
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Reçu — {{ $order->reference }}</title>
            <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            <style>
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body { font-family: 'DM Sans', Arial, sans-serif; background: white; }
                @media print {
                    body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                    @page { margin: 15mm; size: A4; }
                }
            </style>
        </head>
        <body>
            ${printContent}
            <script>
                window.onload = function() {
                    window.print();
                    window.onafterprint = function() { window.close(); };
                };
            <\/script>
        </body>
        </html>
    `);
    win.document.close();
}
</script>
@endsection
@endsection