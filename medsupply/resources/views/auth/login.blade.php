<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MedSupply — Connexion</title>
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

  .login-wrapper {
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

  .login-card {
    background: rgba(255,255,255,0.82);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255,255,255,0.7);
    border-radius: 28px;
    box-shadow: var(--card-shadow), 0 2px 0 rgba(255,255,255,0.9) inset;
    padding: 3rem 3rem 2.5rem;
    width: 100%;
    max-width: 460px;
  }

  .logo-area {
    text-align: center;
    margin-bottom: 2.2rem;
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

  /* Error alert */
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
    animation: fadeIn 0.35s ease;
  }
  .alert-success {
    background: #e6f9f2;
    border-radius: 10px;
    padding: 10px 14px;
    margin-bottom: 1.2rem;
    color: #3cbf99;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 8px;
    animation: fadeIn 0.35s ease;
  }

  .form-group { margin-bottom: 1.2rem; }
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
  .form-input.input-error {
    border-color: #e05d5d;
    background: #fff8f8;
  }
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

  .forgot-link {
    font-size: 0.82rem;
    color: var(--blue-dark);
    text-decoration: none;
    float: right;
    margin-top: 4px;
    transition: opacity 0.2s;
  }
  .forgot-link:hover { opacity: 0.7; }

  .btn-login {
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
    position: relative;
    overflow: hidden;
    margin-top: 0.8rem;
    letter-spacing: 0.3px;
  }
  .btn-login::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 100%);
    opacity: 0;
    transition: opacity 0.3s;
  }
  .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(91,155,213,0.35); }
  .btn-login:hover::before { opacity: 1; }
  .btn-login:active { transform: translateY(0); }

  .btn-login .ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.25);
    transform: scale(0);
    animation: rippleAnim 0.6s linear;
    pointer-events: none;
  }
  @keyframes rippleAnim {
    to { transform: scale(4); opacity: 0; }
  }

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

  .register-link {
    text-align: center;
    font-size: 0.85rem;
    color: var(--text-soft);
  }
  .register-link a {
    color: var(--mint-dark);
    text-decoration: none;
    font-weight: 600;
    transition: opacity 0.2s;
  }
  .register-link a:hover { opacity: 0.75; }

  .role-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    background: var(--blue-soft);
    border-radius: 10px;
    margin-bottom: 1.4rem;
    font-size: 0.82rem;
    color: var(--blue-dark);
    font-weight: 500;
    animation: fadeIn 0.4s ease;
  }
  @keyframes fadeIn { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }
  .role-indicator i { font-size: 14px; }

  .toast-container {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 9999;
  }
  .toast-msg {
    background: white;
    border-left: 3px solid var(--mint-dark);
    border-radius: 12px;
    padding: 12px 18px;
    font-size: 0.86rem;
    color: var(--text);
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 10px;
    animation: slideInRight 0.4s cubic-bezier(0.22,1,0.36,1);
  }
  @keyframes slideInRight {
    from { transform: translateX(100px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
  }
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
<div class="particle" style="left:45%;animation-duration:16s;animation-delay:7s;width:5px;height:5px;"></div>

<div class="login-wrapper">
  <div class="login-card">

    <div class="logo-area">
      <div class="logo-icon"><i class="fa-solid fa-heart-pulse"></i></div>
      <div class="logo-title">Med<span>Supply</span></div>
      <div class="logo-sub">Plateforme intelligente des achats médicaux</div>
    </div>

    <div class="role-selector">
      <button class="role-btn active" data-role="admin" onclick="selectRole(this)">
        <i class="fa-solid fa-shield-halved"></i> Administrateur
      </button>
      <button class="role-btn" data-role="chef" onclick="selectRole(this)">
        <i class="fa-solid fa-hospital"></i> Chef Hôpital
      </button>
      <button class="role-btn" data-role="fournisseur" onclick="selectRole(this)">
        <i class="fa-solid fa-truck-medical"></i> Fournisseur
      </button>
    </div>

    <div class="role-indicator" id="roleIndicator">
      <i class="fa-solid fa-shield-halved"></i>
      <span id="roleText">Connexion Administrateur — accès complet plateforme</span>
    </div>

    {{-- ✅ Messages d'erreur Laravel --}}
    @if ($errors->any())
    <div class="alert-error">
        <i class="fa-solid fa-circle-xmark"></i>
        {{ $errors->first() }}
    </div>
    @endif

    {{-- ✅ Message de succès (ex: après reset password) --}}
    @if (session('status'))
    <div class="alert-success">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('status') }}
    </div>
    @endif

    {{-- ✅ Message d'erreur session --}}
    @if (session('error'))
    <div class="alert-error">
        <i class="fa-solid fa-circle-xmark"></i>
        {{ session('error') }}
    </div>
    @endif

    <div class="form-group">
      <label class="form-label">Adresse email</label>
      <div class="input-wrap">
        <i class="fa-regular fa-envelope input-icon"></i>
        <input type="email"
               class="form-input @error('email') input-error @enderror"
               id="email"
               placeholder="votre@email.com"
               value="{{ old('email') }}">
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">
        Mot de passe
        <a href="{{ route('password.request') }}" class="forgot-link">OUBLIÉ ?</a>
      </label>
      <div class="input-wrap">
        <i class="fa-solid fa-lock input-icon"></i>
        <input type="password"
               class="form-input @error('password') input-error @enderror"
               id="password"
               placeholder="••••••••">
        <button class="toggle-pw" onclick="togglePw()" id="toggleBtn" type="button">
          <i class="fa-regular fa-eye" id="eyeIcon"></i>
        </button>
      </div>
    </div>

    <div style="display:flex;align-items:center;gap:8px;margin-bottom:0.5rem;">
      <input type="checkbox" id="remember" style="accent-color:var(--blue);cursor:pointer;">
      <label for="remember" style="font-size:0.84rem;color:var(--text-soft);cursor:pointer;">Se souvenir de moi</label>
    </div>

    <button class="btn-login" onclick="doLogin(this)" type="button">
      <span id="loginBtnText"><i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Se connecter</span>
    </button>

    <div class="divider">ou</div>
    <div class="register-link">
      Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a>
    </div>
  </div>
</div>

<div class="toast-container" id="toastContainer"></div>

<script>
  const roles = {
    admin: {
      icon: 'fa-shield-halved',
      text: "Connexion Administrateur — accès complet plateforme",
      color: 'var(--blue-soft)',
      accent: 'var(--blue-dark)'
    },
    chef: {
      icon: 'fa-hospital',
      text: "Connexion Chef d'Hôpital — gestion stock & commandes",
      color: 'var(--mint)',
      accent: 'var(--mint-dark)'
    },
    fournisseur: {
      icon: 'fa-truck-medical',
      text: "Connexion Fournisseur — catalogue & livraisons",
      color: 'var(--lavender)',
      accent: 'var(--lavender-dark)'
    }
  };

  let currentRole = 'admin';

  function selectRole(btn) {
    document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentRole = btn.dataset.role;
    const r = roles[currentRole];
    const ind = document.getElementById('roleIndicator');
    ind.style.background = r.color;
    ind.style.color = r.accent;
    ind.innerHTML = `<i class="fa-solid ${r.icon}"></i><span>${r.text}</span>`;
    ind.style.animation = 'none';
    void ind.offsetWidth;
    ind.style.animation = 'fadeIn 0.35s ease';
  }

  function togglePw() {
    const inp = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (inp.type === 'password') {
      inp.type = 'text';
      icon.className = 'fa-regular fa-eye-slash';
    } else {
      inp.type = 'password';
      icon.className = 'fa-regular fa-eye';
    }
  }

  function doLogin(btn) {
    const email = document.getElementById('email').value.trim();
    const pw    = document.getElementById('password').value;

    if (!email || !pw) {
      showToast('Veuillez remplir tous les champs.', 'warning');
      return;
    }

    const txt = document.getElementById('loginBtnText');
    txt.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Connexion en cours...';
    btn.disabled = true;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/login';

    const token = document.createElement('input');
    token.type  = 'hidden';
    token.name  = '_token';
    token.value = '{{ csrf_token() }}';
    form.appendChild(token);

    const emailInput = document.createElement('input');
    emailInput.type  = 'hidden';
    emailInput.name  = 'email';
    emailInput.value = email;
    form.appendChild(emailInput);

    const pwInput = document.createElement('input');
    pwInput.type  = 'hidden';
    pwInput.name  = 'password';
    pwInput.value = pw;
    form.appendChild(pwInput);

    const rememberInput = document.createElement('input');
    rememberInput.type  = 'hidden';
    rememberInput.name  = 'remember';
    rememberInput.value = document.getElementById('remember').checked ? '1' : '0';
    form.appendChild(rememberInput);

    document.body.appendChild(form);
    form.submit();
  }

  // Ripple effect
  document.querySelector('.btn-login').addEventListener('click', function(e) {
    const r = document.createElement('span');
    r.className = 'ripple';
    const rect = this.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    r.style.width  = r.style.height = size + 'px';
    r.style.left   = (e.clientX - rect.left - size/2) + 'px';
    r.style.top    = (e.clientY - rect.top  - size/2) + 'px';
    this.appendChild(r);
    setTimeout(() => r.remove(), 700);
  });

  function showToast(msg, type) {
    const icons  = { success: 'fa-circle-check', warning: 'fa-triangle-exclamation', error: 'fa-circle-xmark' };
    const colors = { success: 'var(--mint-dark)', warning: '#f5a623', error: '#e05d5d' };
    const t = document.createElement('div');
    t.className = 'toast-msg';
    t.style.borderLeftColor = colors[type];
    t.innerHTML = `<i class="fa-solid ${icons[type]}" style="color:${colors[type]}"></i> ${msg}`;
    document.getElementById('toastContainer').appendChild(t);
    setTimeout(() => t.remove(), 3500);
  }

  // Extra particles
  for (let i = 0; i < 3; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.left = Math.random()*100 + '%';
    p.style.animationDuration = (10 + Math.random()*8) + 's';
    p.style.animationDelay    = Math.random()*8 + 's';
    document.body.appendChild(p);
  }
</script>
</body>
</html>