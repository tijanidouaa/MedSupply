<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 13px; color: #1e2d45; background: #fff; padding: 40px; }

  /* Header */
  .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 36px; padding-bottom: 24px; border-bottom: 2px solid #5b9bd5; }
  .logo { font-size: 26px; font-weight: 800; color: #3a7bbf; }
  .logo span { color: #3cbf99; }
  .invoice-title { text-align: right; }
  .invoice-title h1 { font-size: 20px; color: #1e2d45; font-weight: 700; margin-bottom: 4px; }
  .invoice-title .ref { font-family: monospace; font-size: 13px; color: #5b9bd5; font-weight: 700; }
  .invoice-title .date { font-size: 12px; color: #8a9ab5; margin-top: 4px; }

  /* Badges */
  .status-badge { display: inline-block; background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; margin-top: 6px; }

  /* Parties */
  .parties { display: flex; gap: 24px; margin-bottom: 32px; }
  .party { flex: 1; background: #f2f6fc; border-radius: 10px; padding: 18px 20px; }
  .party-title { font-size: 10px; font-weight: 700; color: #8a9ab5; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 10px; }
  .party-name { font-size: 15px; font-weight: 700; color: #1e2d45; margin-bottom: 6px; }
  .party-info { font-size: 12px; color: #5e7291; line-height: 1.7; }

  /* Tableau produits */
  .section-title { font-size: 11px; font-weight: 700; color: #8a9ab5; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 12px; }
  table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
  thead tr { background: #5b9bd5; color: white; }
  thead th { padding: 10px 14px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
  tbody tr:nth-child(even) { background: #f2f6fc; }
  tbody td { padding: 10px 14px; font-size: 12px; color: #1e2d45; border-bottom: 1px solid #e8eef7; }
  .total-row td { font-weight: 700; font-size: 13px; border-top: 2px solid #5b9bd5; background: #ddeeff; }

  /* Tracking */
  .tracking-box { background: #e8e4f8; border-radius: 10px; padding: 16px 20px; margin-bottom: 24px; }
  .tracking-title { font-size: 10px; font-weight: 700; color: #8b7fd4; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px; }
  .tracking-row { display: flex; justify-content: space-between; font-size: 12px; padding: 4px 0; }
  .tracking-label { color: #8a9ab5; }
  .tracking-value { font-weight: 600; color: #1e2d45; font-family: monospace; }
  .tracking-pending { color: #856404; font-style: italic; }

  /* Note */
  .note { background: #fff3cd; border-left: 3px solid #f59e0b; border-radius: 6px; padding: 12px 16px; font-size: 11px; color: #856404; margin-bottom: 24px; }

  /* Footer */
  .footer { border-top: 1px solid #e8eef7; padding-top: 16px; text-align: center; font-size: 11px; color: #8a9ab5; }
  .footer strong { color: #5b9bd5; }
</style>
</head>
<body>

{{-- Header --}}
<div class="header">
  <div>
    <div class="logo">Med<span>Supply</span></div>
    <div style="font-size:11px;color:#8a9ab5;margin-top:4px;">Plateforme de gestion médicale</div>
  </div>
  <div class="invoice-title">
    <h1>FACTURE DE CONFIRMATION</h1>
    <div class="ref">{{ $order->reference }}</div>
    <div class="date">Émise le {{ $order->created_at->format('d/m/Y à H:i') }}</div>
    <div class="status-badge">En attente de validation</div>
  </div>
</div>

{{-- Parties --}}
<div class="parties">
  <div class="party">
    <div class="party-title">🏥 Hôpital (Client)</div>
    <div class="party-name">{{ $order->hospital->name ?? 'N/A' }}</div>
    <div class="party-info">
      {{ $order->hospital->address ?? '' }}<br>
      {{ $order->hospital->email ?? '' }}<br>
      {{ $order->hospital->phone ?? '' }}
    </div>
  </div>
  <div class="party">
    <div class="party-title">🚚 Fournisseur</div>
    <div class="party-name">{{ $order->supplier->name ?? 'N/A' }}</div>
    <div class="party-info">
      {{ $order->supplier->email ?? '' }}<br>
      {{ $order->supplier->phone ?? '' }}
    </div>
  </div>
</div>

{{-- Produits --}}
<div class="section-title">Détail de la commande</div>
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Produit</th>
      <th>Référence</th>
      <th style="text-align:center">Qté</th>
      <th style="text-align:right">Prix unitaire</th>
      <th style="text-align:right">Sous-total</th>
    </tr>
  </thead>
  <tbody>
    @if($order->items && $order->items->count() > 0)
      @foreach($order->items as $i => $item)
      <tr>
        <td style="color:#8a9ab5;">{{ $i + 1 }}</td>
        <td style="font-weight:600;">{{ $item->product->name ?? $item->name ?? '—' }}</td>
        <td style="font-family:monospace;font-size:11px;color:#5b9bd5;">{{ $item->product->reference ?? '—' }}</td>
        <td style="text-align:center;">{{ $item->quantity }}</td>
        <td style="text-align:right;">{{ number_format($item->unit_price ?? 0, 2) }} DH</td>
        <td style="text-align:right;font-weight:600;">{{ number_format(($item->quantity * ($item->unit_price ?? 0)), 2) }} DH</td>
      </tr>
      @endforeach
    @else
      <tr>
        <td colspan="6" style="text-align:center;color:#8a9ab5;padding:20px;">
          Aucun produit détaillé — total global : {{ number_format($order->total, 2) }} DH
        </td>
      </tr>
    @endif
    <tr class="total-row">
      <td colspan="5" style="text-align:right;padding-right:16px;">TOTAL</td>
      <td style="text-align:right;">{{ number_format($order->total, 2) }} DH</td>
    </tr>
  </tbody>
</table>

{{-- Tracking --}}
<div class="tracking-box">
  <div class="tracking-title">📦 Informations de livraison</div>
  <div class="tracking-row">
    <span class="tracking-label">Numéro de tracking</span>
    <span class="{{ $order->tracking_number ? 'tracking-value' : 'tracking-pending' }}">
      {{ $order->tracking_number ?? 'Sera communiqué à l\'expédition' }}
    </span>
  </div>
  <div class="tracking-row">
    <span class="tracking-label">Société de livraison</span>
    <span class="{{ $order->carrier ? 'tracking-value' : 'tracking-pending' }}">
      {{ $order->carrier ?? 'À définir' }}
    </span>
  </div>
  <div class="tracking-row">
    <span class="tracking-label">Date d'expédition</span>
    <span class="{{ $order->shipped_at ? 'tracking-value' : 'tracking-pending' }}">
      {{ $order->shipped_at ? $order->shipped_at->format('d/m/Y') : 'À définir' }}
    </span>
  </div>
</div>

{{-- Note --}}
<div class="note">
  ⚠️ Cette facture est une confirmation de commande. Le numéro de tracking et la société de livraison vous seront communiqués par email dès l'expédition de votre commande.
</div>

{{-- Footer --}}
<div class="footer">
  <strong>MedSupply</strong> — Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }}<br>
  Ce document est une confirmation de commande et ne constitue pas une facture fiscale définitive.
</div>

</body>
</html>