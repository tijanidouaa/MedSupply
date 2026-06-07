<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MedSupply — Réinitialiser le mot de passe</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Fraunces:wght@400;600&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #f2f6fc; --blue: #5b9bd5; --blue-dark: #3a7bbf;
  --mint: #c8f0e4; --mint-dark: #3cbf99;
  --border: rgba(91,155,213,0.15); --text: #1e2d45;
  --text2: #5e7291; --text3: #8a9ab5;
  --card-bg: #ffffff; --peach: #fde8df; --peach-dark: #e07050;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'DM Sans', sans-serif; background: var(--bg); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
.card { background: var(--card-bg); border-radius: 24px; padding: 2.4rem; width: 420px; max-width: 95vw; box-shadow: 0 8px 40px rgba(91,155,213,0.13); border: 1px solid var(--border); }
.logo { display: flex; align-items: center; gap: 10px; margin-bottom: 2rem; justify-content: center; }
.logo-icon { width: 42px; height: 42px; background: linear-gradient(135deg, var(--blue), var(--mint-dark)); border-radius: 12px; display: flex; align-items: center; justify-content: center; }
.logo-icon i { color: white; font-size: 18px; }
.logo-text { font-family: 'Fraunces', serif; font-size: 1.4rem; font-weight: 600; color: var(--text); }
.logo-text span { color: var(--blue-dark); }
h2 { font-family: 'Fraunces', serif; font-size: 1.2rem; color: var(--text); margin-bottom: 0.4rem; text-align: center; }
.subtitle { font-size: 0.82rem; color: var(--text3); text-align: center; margin-bottom: 1.8rem; }
.field { margin-bottom: 1.2rem; }
.field label { display: block; font-size: 0.74rem; font-weight: 700; color: var(--text2); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 7px; }
.field-wrap { display: flex; align-items: center; gap: 10px; background: var(--bg); border: 1.5px solid var(--border); border-radius: 12px; padding: 10px 14px; transition: all 0.2s; }
.field-wrap:focus-within { border-color: var(--blue); background: white; box-shadow: 0 0 0 3px rgba(91,155,213,0.1); }
.field-wrap i { color: var(--text3); font-size: 14px; flex-shrink: 0; }
.field-wrap input { border: none; background: none; outline: none; font-size: 0.88rem; color: var(--text); font-family: 'DM Sans', sans-serif; width: 100%; }
.toggle-pw { cursor: pointer; color: var(--text3); transition: color 0.2s; }
.toggle-pw:hover { color: var(--blue-dark); }
.btn-submit { width: 100%; padding: 12px; background: linear-gradient(135deg, var(--blue), var(--blue-dark)); color: white; border: none; border-radius: 12px; font-size: 0.9rem; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 0.4rem; }
.btn-submit:hover { transform: translateY(-1px); box-shadow: 0 5px 18px rgba(91,155,213,0.35); }
.alert-error { background: var(--peach); color: var(--peach-dark); border-radius: 10px; padding: 10px 14px; font-size: 0.82rem; font-weight: 600; margin-bottom: 1.2rem; }
.strength-bar { height: 4px; border-radius: 4px; margin-top: 6px; background: var(--border); overflow: hidden; }
.strength-fill { height: 100%; border-radius: 4px; transition: all 0.3s; width: 0%; }
.strength-text { font-size: 0.72rem; margin-top: 4px; }
</style>
</head>
<body>
<div class="card">
    <div class="logo">
        <div class="logo-icon"><i class="fa-solid fa-heart-pulse"></i></div>
        <span class="logo-text">Med<span>Supply</span></span>
    </div>

    <h2>Nouveau mot de passe</h2>
    <p class="subtitle">Choisissez un mot de passe sécurisé d'au moins 8 caractères.</p>

    @if($errors->any())
    <div class="alert-error">
        <i class="fa-solid fa-circle-xmark"></i> {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="field">
            <label>Adresse email</label>
            <div class="field-wrap">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" value="{{ old('email', $email) }}" required>
            </div>
        </div>

        <div class="field">
            <label>Nouveau mot de passe</label>
            <div class="field-wrap">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Minimum 8 caractères" required oninput="checkStrength(this.value)">
                <i class="fa-solid fa-eye toggle-pw" onclick="togglePassword('password', this)"></i>
            </div>
            <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
            <div class="strength-text" id="strengthText" style="color:var(--text3);"></div>
        </div>

        <div class="field">
            <label>Confirmer le mot de passe</label>
            <div class="field-wrap">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Répétez le mot de passe" required>
                <i class="fa-solid fa-eye toggle-pw" onclick="togglePassword('password_confirmation', this)"></i>
            </div>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fa-solid fa-key"></i> Réinitialiser le mot de passe
        </button>
    </form>
</div>

<script>
function togglePassword(id, icon) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    icon.className = isText ? 'fa-solid fa-eye toggle-pw' : 'fa-solid fa-eye-slash toggle-pw';
}

function checkStrength(val) {
    const fill = document.getElementById('strengthFill');
    const text = document.getElementById('strengthText');
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { pct: '0%',   color: '',              label: '' },
        { pct: '25%',  color: '#e07050',        label: 'Faible' },
        { pct: '50%',  color: '#f59e0b',        label: 'Moyen' },
        { pct: '75%',  color: '#5b9bd5',        label: 'Bon' },
        { pct: '100%', color: '#3cbf99',        label: 'Excellent' },
    ];
    const level = levels[score];
    fill.style.width = level.pct;
    fill.style.background = level.color;
    text.textContent = level.label;
    text.style.color = level.color;
}
</script>
</body>
</html>