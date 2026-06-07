<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirmation de commande</title>
<style>
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f2f6fc; margin: 0; padding: 0; color: #1e2d45; }
  .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(91,155,213,0.12); }
  .header { background: linear-gradient(135deg, #5b9bd5, #3cbf99); padding: 32px 36px; text-align: center; }
  .header h1 { color: white; font-size: 22px; margin: 0 0 4px; font-weight: 700; }
  .header p { color: rgba(255,255,255,0.85); font-size: 14px; margin: 0; }
  .logo { font-size: 28px; font-weight: 800; color: white; letter-spacing: -0.5px; margin-bottom: 12px; }
  .logo span { color: rgba(255,255,255,0.75); }
  .body { padding: 32px 36px; }
  .badge { display: inline-block; background: #c8f0e4; color: #3cbf99; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; margin-bottom: 20px; }
  .greeting { font-size: 16px; color: #1e2d45; margin-bottom: 8px; font-weight: 600; }
  .text { font-size: 14px; color: #5e7291; line-height: 1.6; margin-bottom: 24px; }
  .info-box { background: #f2f6fc; border-radius: 12px; padding: 20px 24px; margin-bottom: 24px; border-left: 4px solid #5b9bd5; }
  .info-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid rgba(91,155,213,0.12); }
  .info-row:last-child { border-bottom: none; }
  .info-label { font-size: 13px; color: #8a9ab5; font-weight: 500; }
  .info-value { font-size: 13px; color: #1e2d45; font-weight: 600; }
  .ref { font-family: monospace; color: #3a7bbf; font-size: 14px; font-weight: 700; }
  .supplier-box { background: #e8e4f8; border-radius: 12px; padding: 18px 24px; margin-bottom: 24px; }
  .supplier-title { font-size: 12px; font-weight: 700; color: #8b7fd4; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; }
  .supplier-name { font-size: 16px; font-weight: 700; color: #1e2d45; margin-bottom: 4px; }
  .supplier-info { font-size: 13px; color: #5e7291; }
  .note-box { background: #fff3cd; border-radius: 10px; padding: 14px 18px; margin-bottom: 24px; font-size: 13px; color: #856404; border-left: 4px solid #f59e0b; }
  .pdf-note { background: #ddeeff; border-radius: 10px; padding: 14px 18px; margin-bottom: 24px; font-size: 13px; color: #3a7bbf; display: flex; align-items: center; gap: 10px; }
  .btn { display: block; background: linear-gradient(135deg, #5b9bd5, #3a7bbf); color: white; text-align: center; padding: 14px 24px; border-radius: 11px; text-decoration: none; font-weight: 700; font-size: 14px; margin-bottom: 24px; }
  .footer { background: #f2f6fc; padding: 20px 36px; text-align: center; font-size: 12px; color: #8a9ab5; border-top: 1px solid rgba(91,155,213,0.12); }
  .footer strong { color: #5b9bd5; }
</style>
</head>
<body>
<div class="wrapper">

  {{-- Header --}}
  <div class="header">
    <div class="logo">Med<span>Supply</span></div>
    <h1>Commande confirmée ✓</h1>
    <p>Votre commande a été soumise avec succès</p>
  </div>

  {{-- Body --}}
  <div class="body">
    <div class="badge">✓ Commande en attente de validation</div>

    <div class="greeting">Bonjour, {{ $order->hospital->name ?? 'Cher client' }}</div>
    <div class="text">
      Votre commande a bien été enregistrée sur la plateforme MedSupply. Vous trouverez en pièce jointe la facture PDF récapitulative.
      Un administrateur va valider votre commande dans les plus brefs délais.
    </div>

    {{-- Infos commande --}}
    <div class="info-box">
      <div class="info-row">
        <span class="info-label">Référence</span>
        <span class="ref">{{ $order->reference }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">Statut</span>
        <span class="info-value">En attente de validation</span>
      </div>
      <div class="info-row">
        <span class="info-label">Total</span>
        <span class="info-value">{{ number_format($order->total, 2) }} DH</span>
      </div>
      <div class="info-row">
        <span class="info-label">Date</span>
        <span class="info-value">{{ $order->created_at->format('d/m/Y à H:i') }}</span>
      </div>
    </div>

    {{-- Fournisseur --}}
    <div class="supplier-box">
      <div class="supplier-title">🚚 Fournisseur</div>
      <div class="supplier-name">{{ $order->supplier->name ?? '—' }}</div>
      <div class="supplier-info">
        @if($order->supplier->email) 📧 {{ $order->supplier->email }}<br>@endif
        @if($order->supplier->phone) 📞 {{ $order->supplier->phone }}@endif
      </div>
    </div>

    {{-- Note tracking --}}
    <div class="note-box">
      ⚠️ Le numéro de tracking et la société de livraison vous seront communiqués par email dès que votre commande sera expédiée.
    </div>

    {{-- PDF --}}
    <div class="pdf-note">
      📎 La facture de confirmation est jointe à cet email en format PDF.
    </div>

    {{-- CTA --}}
    <a href="{{ url('/hospital/orders/' . $order->id) }}" class="btn">
      Suivre ma commande →
    </a>
  </div>

  {{-- Footer --}}
  <div class="footer">
    <strong>MedSupply</strong> — Plateforme de gestion des fournitures médicales<br>
    Cet email a été envoyé automatiquement, merci de ne pas y répondre.
  </div>

</div>
</body>
</html>