<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MedSupply — Créer un compte</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Fraunces:wght@300;400;600&display=swap" rel="stylesheet">
<style>
  :root {
    --white: #ffffff;
    --bg: #f4f7fb;
    --blue-soft: #ddeeff;
    --blue: #5b9bd5;
    --blue-dark: #3a7bbf;
    --mint: #c8f0e4;
    --mint-dark: #3cbf99;
    --lavender: #e8e4f8;
    --lavender-dark: #8b7fd4;
    --gray-light: #f0f3f8;
    --gray: #8a9ab5;
    --text: #1e2d45;
    --text-soft: #5e7291;
    --card-shadow: 0 8px 40px rgba(91,155,213,0.10);
    --radius: 20px;
    --radius-sm: 12px;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow-y: auto;
    position: relative;
  }
  .bg-blob {
    position: fixed;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.45;
    animation: blobFloat 8s ease-in-out infinite alternate;
    pointer-events: none;
    z-index: 0;
  }
  .blob-1 { width: 500px; height: 500px; background: var(--blue-soft); top: -120px; left: -100px; animation-delay: 0s; }
  .blob-2 { width: 400px; height: 400px; background: var(--mint); bottom: -100px; right: -80px; animation-delay: 2s; }
  .blob-3 { width: 300px; height: 300px; background: var(--lavender); top: 40%; left: 30%; animation-delay: 4s; }
  @keyframes blobFloat {
    0% { transform: translate(0,0) scale(1); }
    100% { transform: translate(20px, 30px) scale(1.08); }
  }
  .particle {
    position: fixed;
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--blue);
    opacity: 0.18;
    animation: particleDrift linear infinite;
    pointer-events: none;
    z-index: 0;
  }
  @keyframes particleDrift {
    0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
    10% { opacity: 0.18; }
    90% { opacity: 0.18; }
    100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
  }
  .register-wrapper {
    position: relative;
    z-index: 10;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    width: 100%;
    min-height: 100vh;
    padding: 2rem;
    overflow-y: auto;
    animation: fadeInUp 0.8s cubic-bezier(0.22,1,0.36,1) both;
  }
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .register-card {
    background: rgba(255,255,255,0.82);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255,255,255,0.7);
    border-radius: 28px;
    box-shadow: var(--card-shadow), 0 2px 0 rgba(255,255,255,0.9) inset;
    padding: 3rem 3rem 2.5rem;
    width: 100%;
    max-width: 500px;
    margin: auto;
  }
  .logo-area {
    text-align: center;
    margin-bottom: 2rem;
  }
  .logo-icon {
    width: 64px; height: 64px;
    background: linear-gradient(135deg, var(--blue) 0%, var(--mint-dark) 100%);
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    box-shadow: 0 8px 24px rgba(91,155,213,0.3);
    animation: iconPulse 3s ease-in-out infinite;
  }
  @keyframes iconPulse {
    0%, 100% { box-shadow: 0 8px 24px rgba(91,155,213,0.3); }
    50% { box-shadow: 0 8px 36px rgba(91,155,213,0.5); }
  }
  .logo-icon i { font-size: 28px; color: white; }
  .logo-title {
    font-family: 'Fraunces', serif;
    font-size: 1.9rem;
    color: var(--text);
    font-weight: 600;
    letter-spacing: -0.5px;
  }
  .logo-title span { color: var(--blue-dark); }
  .logo-sub {
    color: var(--text-soft);
    font-size: 0.88rem;
    margin-top: 0.2rem;
  }
  .role-selector {
    display: flex;
    gap: 8px;
    margin-bottom: 1.8rem;
    background: var(--gray-light);
    border-radius: 14px;
    padding: 5px;
  }
  .role-btn {
    flex: 1;
    padding: 8px 10px;
    border: none;
    border-radius: 10px;
    background: transparent;
    color: var(--text-soft);
    font-size: 0.78rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    white-space: nowrap;
  }
  .role-btn.active {
    background: white;
    color: var(--blue-dark);
    box-shadow: 0 2px 12px rgba(91,155,213,0.15);
    font-weight: 600;
  }
  .role-btn i { font-size: 13px; }
  .alert-error {
    background: #fde8e8;
    border-radius: 10px;
    padding: 10px 14px;
    margin-bottom: 1.2rem;
    color: #e05d5d;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .form-group { margin-bottom: 1.1rem; }
  .form-label {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text-soft);
    margin-bottom: 6px;
    display: block;
    letter-spacing: 0.3px;
    text-transform: uppercase;
  }
  .input-wrap { position: relative; }
  .input-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray);
    font-size: 15px;
    transition: color 0.3s;
    pointer-events: none;
  }
  .form-input {
    width: 100%;
    padding: 13px 16px 13px 42px;
    border: 1.5px solid rgba(91,155,213,0.15);
    border-radius: 12px;
    background: var(--gray-light);
    font-size: 0.92rem;
    color: var(--text);
    font-family: 'DM Sans', sans-serif;
    transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
    outline: none;
  }
  .form-input:focus {
    border-color: var(--blue);
    background: white;
    box-shadow: 0 0 0 4px rgba(91,155,213,0.12);
  }
  .form-input.input-error { border-color: #e05d5d; background: #fff8f8; }
  .input-wrap:focus-within .input-icon { color: var(--blue); }
  .toggle-pw {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--gray);
    cursor: pointer;
    padding: 0;
    font-size: 15px;
    transition: color 0.3s;
  }
  .toggle-pw:hover { color: var(--blue); }
  .btn-register {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, var(--blue) 0%, var(--blue-dark) 100%);
    border: none;
    border-radius: 14px;
    color: white;
    font-size: 0.96rem;
    font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
    margin-top: 0.8rem;
    letter-spacing: 0.3px;
    position: relative;
    overflow: hidden;
  }
  .btn-register:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(91,155,213,0.35); }
  .btn-register:active { transform: translateY(0); }
  .divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 1.4rem 0;
    color: var(--gray);
    font-size: 0.78rem;
  }
  .divider::before, .divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(91,155,213,0.15);
  }
  .login-link {
    text-align: center;
    font-size: 0.85rem;
    color: var(--text-soft);
  }
  .login-link a {
    color: var(--mint-dark);
    text-decoration: none;
    font-weight: 600;
    transition: opacity 0.2s;
  }
  .login-link a:hover { opacity: 0.75; }

  /* Supplier-only fields */
  .supplier-fields { display: none; }
  .supplier-fields.show { display: block; }

  @keyframes fadeIn { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }
</style>
</head>
<body>

<div class="bg-blob blob-1"></div>
<div class="bg-blob blob-2"></div>
<div class="bg-blob blob-3"></div>
<div class="particle" style="left:10%;animation-duration:12s;animation-delay:0s;"></div>
<div class="particle" style="left:25%;animation-duration:15s;animation-delay:3s;width:4px;height:4px;background:var(--mint-dark);"></div>
<div class="particle" style="left:60%;animation-duration:10s;animation-delay:1.5s;width:8px;height:8px;background:var(--lavender-dark);opacity:0.12;"></div>
<div class="particle" style="left:80%;animation-duration:13s;animation-delay:5s;"></div>

<div class="register-wrapper">
  <div class="register-card">

    <div class="logo-area">
      <div class="logo-icon"><i class="fa-solid fa-heart-pulse"></i></div>
      <div class="logo-title">Med<span>Supply</span></div>
      <div class="logo-sub">Créer un nouveau compte</div>
    </div>

    {{-- Sélecteur de rôle --}}
    <div class="role-selector">
      <button class="role-btn active" data-role="hospital_chief" onclick="selectRole(this)" type="button">
        <i class="fa-solid fa-hospital"></i> Chef Hôpital
      </button>
      <button class="role-btn" data-role="supplier" onclick="selectRole(this)" type="button">
        <i class="fa-solid fa-truck-medical"></i> Fournisseur
      </button>
    </div>

    @if ($errors->any())
    <div class="alert-error">
      <i class="fa-solid fa-circle-xmark"></i>
      {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}" id="registerForm">
      @csrf
      <input type="hidden" name="role" id="roleInput" value="hospital_chief">

      <div class="form-group">
        <label class="form-label">Nom complet</label>
        <div class="input-wrap">
          <i class="fa-solid fa-user input-icon"></i>
          <input type="text" name="name" class="form-input @error('name') input-error @enderror"
                 placeholder="Dr. Prénom Nom" value="{{ old('name') }}" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Adresse email</label>
        <div class="input-wrap">
          <i class="fa-regular fa-envelope input-icon"></i>
          <input type="email" name="email" class="form-input @error('email') input-error @enderror"
                 placeholder="votre@email.com" value="{{ old('email') }}" required>
        </div>
      </div>

      {{-- Champs spécifiques fournisseur --}}
      <div class="supplier-fields" id="supplierFields">
        <div class="form-group">
          <label class="form-label">Nom de la société</label>
          <div class="input-wrap">
            <i class="fa-solid fa-building input-icon"></i>
            <input type="text" name="company_name" class="form-input @error('company_name') input-error @enderror"
                   placeholder="Ex: MedCo Maroc" value="{{ old('company_name') }}">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Téléphone</label>
          <div class="input-wrap">
            <i class="fa-solid fa-phone input-icon"></i>
            <input type="text" name="phone" class="form-input @error('phone') input-error @enderror"
                   placeholder="0522334455" value="{{ old('phone') }}">
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Mot de passe</label>
        <div class="input-wrap">
          <i class="fa-solid fa-lock input-icon"></i>
          <input type="password" name="password" class="form-input @error('password') input-error @enderror"
                 placeholder="••••••••" id="password" required>
          <button class="toggle-pw" type="button" onclick="togglePw('password', 'eye1')">
            <i class="fa-regular fa-eye" id="eye1"></i>
          </button>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Confirmer le mot de passe</label>
        <div class="input-wrap">
          <i class="fa-solid fa-lock input-icon"></i>
          <input type="password" name="password_confirmation" class="form-input"
                 placeholder="••••••••" id="password2" required>
          <button class="toggle-pw" type="button" onclick="togglePw('password2', 'eye2')">
            <i class="fa-regular fa-eye" id="eye2"></i>
          </button>
        </div>
      </div>

      <button class="btn-register" type="submit">
        <i class="fa-solid fa-user-plus me-2"></i>Créer mon compte
      </button>
    </form>

    <div class="divider">ou</div>
    <div class="login-link">
      Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
    </div>

  </div>
</div>

<script>
  let currentRole = 'hospital_chief';

  function selectRole(btn) {
    document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentRole = btn.dataset.role;
    document.getElementById('roleInput').value = currentRole;

    const supplierFields = document.getElementById('supplierFields');
    if (currentRole === 'supplier') {
      supplierFields.classList.add('show');
      supplierFields.style.animation = 'fadeIn 0.35s ease';
    } else {
      supplierFields.classList.remove('show');
    }
  }

  function togglePw(inputId, iconId) {
    const inp = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (inp.type === 'password') {
      inp.type = 'text';
      icon.className = 'fa-regular fa-eye-slash';
    } else {
      inp.type = 'password';
      icon.className = 'fa-regular fa-eye';
    }
  }
</script>
</body>
</html>