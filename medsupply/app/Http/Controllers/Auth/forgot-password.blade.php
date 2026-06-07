<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MedSupply — Mot de passe oublié</title>
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
.subtitle { font-size: 0.82rem; color: var(--text3); text-align: center; margin-bottom: 1.8rem; line-height: 1.5; }
.field { margin-bottom: 1.2rem; }
.field label { display: block; font-size: 0.74rem; font-weight: 700; color: var(--text2); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 7px; }
.field-wrap { display: flex; align-items: center; gap: 10px; background: var(--bg); border: 1.5px solid var(--border); border-radius: 12px; padding: 10px 14px; transition: all 0.2s; }
.field-wrap:focus-within { border-color: var(--blue); background: white; box-shadow: 0 0 0 3px rgba(91,155,213,0.1); }
.field-wrap i { color: var(--text3); font-size: 14px; flex-shrink: 0; }
.field-wrap input { border: none; background: none; outline: none; font-size: 0.88rem; color: var(--text); font-family: 'DM Sans', sans-serif; width: 100%; }
.btn-submit { width: 100%; padding: 12px; background: linear-gradient(135deg, var(--blue), var(--blue-dark)); color: white; border: none; border-radius: 12px; font-size: 0.9rem; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 0.4rem; }
.btn-submit:hover { transform: translateY(-1px); box-shadow: 0 5px 18px rgba(91,155,213,0.35); }
.alert-success { background: var(--mint); color: var(--mint-dark); border-radius: 10px; padding: 10px 14px; font-size: 0.82rem; font-weight: 600; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 8px; }
.alert-error { background: var(--peach); color: var(--peach-dark); border-radius: 10px; padding: 10px 14px; font-size: 0.82rem; font-weight: 600; margin-bottom: 1.2rem; }
.back-link { display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 1.4rem; font-size: 0.82rem; color: var(--text3); text-decoration: none; transition: color 0.2s; }
.back-link:hover { color: var(--blue-dark); }
</style>
</head>
<body>
<div class="card">
    <div class="logo">
        <div class="logo-icon"><i class="fa-solid fa-heart-pulse"></i></div>
        <span class="logo-text">Med<span>Supply</span></span>
    </div>

    <h2>Mot de passe oublié ?</h2>
    <p class="subtitle">Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>

    @if(session('success'))
    <div class="alert-success">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert-error">
        <i class="fa-solid fa-circle-xmark"></i> {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="field">
            <label>Adresse email</label>
            <div class="field-wrap">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" placeholder="votre@email.com" value="{{ old('email') }}" required autofocus>
            </div>
        </div>
        <button type="submit" class="btn-submit">
            <i class="fa-solid fa-paper-plane"></i> Envoyer le lien
        </button>
    </form>

    <a href="{{ route('login') }}" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Retour à la connexion
    </a>
</div>
</body>
</html>