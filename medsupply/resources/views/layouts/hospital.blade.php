<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MedSupply – @yield('title', 'Dashboard')</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Fraunces:wght@400;600&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #f2f6fc; --white: #ffffff; --blue-soft: #ddeeff; --blue: #5b9bd5; --blue-dark: #3a7bbf;
  --mint: #c8f0e4; --mint-dark: #3cbf99; --lavender: #e8e4f8; --lavender-dark: #8b7fd4;
  --peach: #fde8df; --peach-dark: #e07050; --border: rgba(91,155,213,0.12);
  --text: #1e2d45; --text2: #5e7291; --text3: #8a9ab5;
  --sidebar-bg: #ffffff; --topbar-bg: rgba(255,255,255,0.92); --card-bg: #ffffff;
  --sidebar-w: 260px; --shadow: 0 4px 24px rgba(91,155,213,0.09); --radius: 16px;
}
[data-theme="dark"] {
  --bg: #0f1624; --white: #1a2336; --blue-soft: rgba(91,155,213,0.15);
  --mint: rgba(60,191,153,0.15); --lavender: rgba(139,127,212,0.15); --peach: rgba(224,112,80,0.15);
  --border: rgba(255,255,255,0.07); --text: #e8f0fe; --text2: #8ba3c7; --text3: #556280;
  --sidebar-bg: #131d2e; --topbar-bg: rgba(19,29,46,0.95); --card-bg: #1a2336;
  --shadow: 0 4px 24px rgba(0,0,0,0.3);
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; transition: background 0.3s, color 0.3s; }
.sidebar { width: var(--sidebar-w); background: var(--sidebar-bg); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 100; box-shadow: 4px 0 30px rgba(91,155,213,0.06); overflow-y: auto; transition: background 0.3s; }
.sidebar-logo { padding: 1.4rem 1.2rem 1rem; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border); flex-shrink: 0; }
.logo-icon { width: 38px; height: 38px; background: linear-gradient(135deg, var(--blue), var(--mint-dark)); border-radius: 11px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(91,155,213,0.3); }
.logo-icon i { color: white; font-size: 17px; }
.logo-text { font-family: 'Fraunces', serif; font-size: 1.25rem; font-weight: 600; color: var(--text); }
.logo-text span { color: var(--blue-dark); }
.sidebar-section { padding: 1rem 1rem 0.3rem; font-size: 0.68rem; font-weight: 700; color: var(--text3); letter-spacing: 1px; text-transform: uppercase; }
.nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 12px; margin: 2px 8px; border-radius: 11px; font-size: 0.86rem; font-weight: 500; color: var(--text2); cursor: pointer; transition: all 0.25s; text-decoration: none; position: relative; }
.nav-item:hover { background: var(--blue-soft); color: var(--blue-dark); }
.nav-item.active { background: linear-gradient(135deg, var(--blue-soft), var(--mint)); color: var(--blue-dark); font-weight: 600; }
.nav-item.active::before { content: ''; position: absolute; left: -8px; top: 25%; bottom: 25%; width: 3px; background: var(--blue); border-radius: 0 3px 3px 0; }
.nav-item i { width: 18px; text-align: center; font-size: 14px; }
.nav-badge { margin-left: auto; font-size: 0.65rem; font-weight: 700; padding: 2px 6px; border-radius: 20px; background: var(--blue); color: white; }
.nav-badge.red { background: #e05d5d; }
.sidebar-footer { margin-top: auto; padding: 1rem; border-top: 1px solid var(--border); flex-shrink: 0; }
.user-card { display: flex; align-items: center; gap: 9px; padding: 9px 11px; border-radius: 11px; background: var(--bg); }
.user-avatar { width: 34px; height: 34px; border-radius: 9px; background: linear-gradient(135deg, var(--mint-dark), var(--blue)); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 13px; flex-shrink: 0; }
.user-name { font-size: 0.82rem; font-weight: 600; color: var(--text); }
.user-role { font-size: 0.7rem; color: var(--text3); }

/* ✅ Bouton déconnexion isolé - pas de form qui capture les clics */
.logout-btn {
  background: none;
  border: none;
  cursor: pointer;
  color: var(--text3);
  padding: 4px 6px;
  border-radius: 7px;
  transition: all 0.2s;
  font-size: 14px;
  display: flex;
  align-items: center;
}
.logout-btn:hover { background: var(--peach); color: var(--peach-dark); }

.main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
.topbar { background: var(--topbar-bg); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); padding: 0 1.8rem; height: 62px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 99; }
.topbar-title h1 { font-family: 'Fraunces', serif; font-size: 1.2rem; color: var(--text); font-weight: 600; }
.topbar-title p { font-size: 0.74rem; color: var(--text3); }
.topbar-right { display: flex; align-items: center; gap: 10px; }
.search-box { display: flex; align-items: center; gap: 7px; background: var(--bg); border: 1.5px solid transparent; border-radius: 11px; padding: 7px 12px; transition: all 0.3s; width: 200px; }
.search-box:focus-within { border-color: var(--blue); background: var(--card-bg); width: 250px; }
.search-box input { border: none; background: none; outline: none; font-size: 0.84rem; color: var(--text); font-family: 'DM Sans', sans-serif; width: 100%; }
.topbar-icon-btn { width: 38px; height: 38px; border-radius: 11px; border: 1.5px solid var(--border); background: var(--card-bg); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; color: var(--text2); text-decoration: none; position: relative; }
.topbar-icon-btn:hover { background: var(--blue-soft); border-color: var(--blue); color: var(--blue-dark); }
.notif-dot { position: absolute; top: 6px; right: 6px; width: 7px; height: 7px; background: #e05d5d; border-radius: 50%; border: 2px solid var(--card-bg); animation: pulse 2s infinite; }
@keyframes pulse { 0%,100%{transform:scale(1);}50%{transform:scale(1.3);} }
@keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
.notif-wrapper { position: relative; }
.notif-dropdown { display: none; position: absolute; top: calc(100% + 10px); right: 0; width: 320px; background: var(--card-bg); border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 12px 40px rgba(0,0,0,0.15); z-index: 999; overflow: hidden; }
.notif-dropdown.open { display: block; animation: fadeUp 0.2s ease; }
.notif-header { padding: 14px 16px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.notif-header span { font-size: 0.85rem; font-weight: 700; color: var(--text); }
.notif-header a { font-size: 0.75rem; color: var(--blue); text-decoration: none; }
.notif-item { padding: 11px 16px; display: flex; gap: 10px; align-items: flex-start; border-bottom: 1px solid var(--border); transition: background 0.15s; cursor: pointer; }
.notif-item:last-child { border-bottom: none; }
.notif-item:hover { background: var(--bg); }
.notif-icon { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0; }
.notif-text { font-size: 0.78rem; color: var(--text); line-height: 1.4; }
.notif-time { font-size: 0.7rem; color: var(--text3); margin-top: 2px; }
.content { padding: 1.8rem; flex: 1; }
.ms-card { background: var(--card-bg); border-radius: var(--radius); box-shadow: var(--shadow); border: 1px solid var(--border); animation: fadeUp 0.5s cubic-bezier(0.22,1,0.36,1) both; overflow: hidden; }
.ms-card-header { display: flex; align-items: center; justify-content: space-between; padding: 1.1rem 1.4rem 0.9rem; border-bottom: 1px solid var(--border); }
.ms-card-title { font-size: 0.9rem; font-weight: 700; color: var(--text); }
.ms-card-body { padding: 1.2rem 1.4rem; }
.ms-table { width: 100%; border-collapse: collapse; }
.ms-table th { font-size: 0.7rem; font-weight: 700; color: var(--text3); text-transform: uppercase; letter-spacing: 0.5px; padding: 8px 12px; border-bottom: 1px solid var(--border); }
.ms-table td { font-size: 0.83rem; color: var(--text); padding: 10px 12px; border-bottom: 1px solid var(--border); vertical-align: middle; }
.ms-table tr:last-child td { border-bottom: none; }
.ms-table tbody tr:hover { background: var(--bg); }
.ms-pill { padding: 3px 9px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
.pill-pending { background: #fff3cd; color: #856404; }
.pill-validated { background: var(--blue-soft); color: var(--blue-dark); }
.pill-confirmed { background: var(--lavender); color: var(--lavender-dark); }
.pill-delivered { background: var(--mint); color: var(--mint-dark); }
.pill-cancelled { background: var(--peach); color: var(--peach-dark); }
.btn-ms-primary { background: linear-gradient(135deg, var(--blue), var(--blue-dark)); color: white; border: none; border-radius: 11px; padding: 9px 18px; font-size: 0.84rem; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 7px; text-decoration: none; }
.btn-ms-primary:hover { transform: translateY(-1px); box-shadow: 0 5px 18px rgba(91,155,213,0.35); color: white; }
.btn-ms-secondary { background: var(--card-bg); color: var(--text2); border: 1.5px solid var(--border); border-radius: 11px; padding: 9px 18px; font-size: 0.84rem; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 7px; text-decoration: none; }
.btn-ms-secondary:hover { background: var(--bg); border-color: var(--blue); color: var(--blue-dark); }
.ms-input { padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 0.84rem; font-family: 'DM Sans', sans-serif; color: var(--text); background: var(--card-bg); outline: none; transition: all 0.2s; width: 100%; }
.ms-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(91,155,213,0.1); }
.ms-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238a9ab5' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 32px; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.4rem; }
.page-header h2 { font-family: 'Fraunces', serif; font-size: 1.3rem; font-weight: 600; color: var(--text); margin-bottom: 2px; }
.page-header p { font-size: 0.8rem; color: var(--text3); }
.icon-btn { width: 30px; height: 30px; border-radius: 8px; border: 1.5px solid var(--border); background: var(--card-bg); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; color: var(--text2); text-decoration: none; }
.icon-btn:hover { background: var(--blue-soft); color: var(--blue-dark); }
.kpi-card { background: var(--card-bg); border-radius: var(--radius); padding: 1.3rem; box-shadow: var(--shadow); position: relative; overflow: hidden; animation: fadeUp 0.5s cubic-bezier(0.22,1,0.36,1) both; transition: transform 0.25s, box-shadow 0.25s; border: 1px solid var(--border); }
.kpi-card:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(91,155,213,0.14); }
.kpi-icon { width: 40px; height: 40px; border-radius: 11px; display: flex; align-items: center; justify-content: center; margin-bottom: 0.8rem; font-size: 16px; }
.kpi-label { font-size: 0.75rem; color: var(--text3); font-weight: 500; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.4px; }
.kpi-value { font-family: 'Fraunces', serif; font-size: 1.8rem; font-weight: 600; color: var(--text); line-height: 1; }
</style>
</head>
<body>

{{-- ✅ Form logout isolé en dehors de tout autre contenu --}}
<form id="logoutForm" action="/logout" method="POST" style="display:none;">
    @csrf
</form>

<nav class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="fa-solid fa-heart-pulse"></i></div>
    <span class="logo-text">Med<span>Supply</span></span>
  </div>

  <div class="sidebar-section">Principal</div>
  <a class="nav-item {{ request()->is('hospital/dashboard*') ? 'active' : '' }}" href="/hospital/dashboard">
    <i class="fa-solid fa-chart-pie"></i> Dashboard
  </a>
  <a class="nav-item {{ request()->is('hospital/orders*') ? 'active' : '' }}" href="/hospital/orders">
    <i class="fa-solid fa-file-invoice"></i> Mes Commandes
  </a>
  <a class="nav-item {{ request()->is('hospital/catalog*') ? 'active' : '' }}" href="/hospital/catalog">
    <i class="fa-solid fa-store"></i> Catalogue
  </a>

  <div class="sidebar-section">Gestion</div>
  <a class="nav-item {{ request()->is('hospital/suppliers*') ? 'active' : '' }}" href="/hospital/suppliers">
    <i class="fa-solid fa-truck-medical"></i> Fournisseurs
  </a>
  <a class="nav-item {{ request()->is('hospital/alerts*') ? 'active' : '' }}" href="/hospital/alerts">
    <i class="fa-solid fa-triangle-exclamation"></i> Alertes Stock
  </a>

  <div class="sidebar-section">Compte</div>
  <a class="nav-item {{ request()->is('hospital/ai-assistant*') ? 'active' : '' }}" href="/hospital/ai-assistant">
    <i class="fa-solid fa-robot"></i> Assistant IA
  </a>
  <a class="nav-item {{ request()->is('hospital/settings*') ? 'active' : '' }}" href="{{ route('hospital.settings.index') }}">
    <i class="fa-solid fa-gear"></i> Paramètres
  </a>

  <div class="sidebar-footer">
    <div class="user-card">
      <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'H', 0, 2)) }}</div>
      <div style="flex:1;min-width:0;">
        <div class="user-name">{{ auth()->user()->name ?? 'Chef Hopital' }}</div>
        <div class="user-role">{{ auth()->user()->role ?? 'hospital_chief' }}</div>
      </div>
      {{-- ✅ Bouton logout simple — soumet le form isolé --}}
      <button type="button" class="logout-btn" onclick="document.getElementById('logoutForm').submit()" title="Déconnexion">
        <i class="fa-solid fa-right-from-bracket"></i>
      </button>
    </div>
  </div>
</nav>

<div class="main">
  <div class="topbar">
    <div class="topbar-title">
      <h1>@yield('page-title', 'Dashboard')</h1>
      <p>@yield('page-subtitle', 'MedSupply · Hopital')</p>
    </div>
    <div class="topbar-right">
      <div class="search-box">
        <i class="fa-solid fa-magnifying-glass" style="color:var(--text3);font-size:13px;"></i>
        <input type="text" placeholder="Rechercher...">
      </div>
      <div class="notif-wrapper" id="notifWrapper">
        <div class="topbar-icon-btn" id="notifBtn">
          <i class="fa-regular fa-bell"></i>
          <span class="notif-dot"></span>
        </div>
        <div class="notif-dropdown" id="notifDropdown">
          <div class="notif-header">
            <span>Notifications</span>
            <a href="#">Tout marquer lu</a>
          </div>
          <div class="notif-item">
            <div class="notif-icon" style="background:var(--peach);color:var(--peach-dark)"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div>
              <div class="notif-text">Stock critique détecté</div>
              <div class="notif-time">il y a 1h</div>
            </div>
          </div>
          <div class="notif-item">
            <div class="notif-icon" style="background:var(--mint);color:var(--mint-dark)"><i class="fa-solid fa-truck"></i></div>
            <div>
              <div class="notif-text">Commande livrée</div>
              <div class="notif-time">il y a 3h</div>
            </div>
          </div>
        </div>
      </div>
      <div class="topbar-icon-btn" onclick="toggleTheme()" title="Mode sombre">
        <i class="fa-solid fa-moon" id="themeIcon"></i>
      </div>
    </div>
  </div>

  <div class="content">
    @if(session('success'))
    <div style="background:var(--mint);color:var(--mint-dark);padding:10px 16px;border-radius:11px;margin-bottom:1rem;font-size:0.84rem;font-weight:600;">
      <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="background:var(--peach);color:var(--peach-dark);padding:10px 16px;border-radius:11px;margin-bottom:1rem;font-size:0.84rem;font-weight:600;">
      <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
    </div>
    @endif
    @yield('content')
  </div>
</div>

<script>
function toggleTheme() {
    const html = document.documentElement;
    const icon = document.getElementById('themeIcon');
    const isDark = html.getAttribute('data-theme') === 'dark';
    html.setAttribute('data-theme', isDark ? 'light' : 'dark');
    icon.className = isDark ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
    localStorage.setItem('theme', isDark ? 'light' : 'dark');
}
(function() {
    const saved = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', saved);
    if (saved === 'dark') document.getElementById('themeIcon').className = 'fa-solid fa-sun';
})();

{{-- ✅ toggleNotif via event listener ciblé — ne capture plus les clics globaux --}}
document.getElementById('notifBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    document.getElementById('notifDropdown').classList.toggle('open');
});
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('notifWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('notifDropdown').classList.remove('open');
    }
});
</script>
@yield('scripts')
</body>
</html>