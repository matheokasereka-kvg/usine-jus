<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - Usine Jus</title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; font-family: Arial, Helvetica, sans-serif; background: #f5f7fb; color: #162033; }
        form { width: min(420px, calc(100vw - 32px)); background: #fff; border: 1px solid #e3e8f0; border-radius: 8px; padding: 26px; box-shadow: 0 8px 24px rgba(16, 32, 51, .08); }
        h1 { margin: 0 0 6px; }
        p { margin: 0 0 22px; color: #68758a; }
        label { display: grid; gap: 6px; margin-bottom: 14px; color: #41506a; }
        input { border: 1px solid #cfd8e3; border-radius: 6px; padding: 11px; font: inherit; }
        button { width: 100%; border: 0; border-radius: 6px; padding: 11px; background: #1f6feb; color: #fff; font: inherit; cursor: pointer; }
        .error { background: #fff1f1; color: #a22121; border: 1px solid #f2c0c0; padding: 10px; border-radius: 6px; margin-bottom: 14px; }
    </style>
</head>
<body>
<form method="post" action="{{ route('login.store') }}">
    @csrf
    <h1>Usine Jus</h1>
    <p>Connecte-toi pour tester le dashboard et les boutons.</p>
    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif
    <label>Email
        <input name="email" type="email" value="{{ old('email', 'admin@usine-jus.test') }}" required autofocus>
    </label>
    <label>Mot de passe
        <input name="password" type="password" value="password" required>
    </label>
    <button type="submit">Connexion</button>
</form>
</body>
</html>
