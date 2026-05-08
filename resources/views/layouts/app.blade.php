<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Usine Jus') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: #f5f7fb; color: #162033; }
        a { color: inherit; text-decoration: none; }
        .app { min-height: 100vh; display: grid; grid-template-columns: 240px 1fr; }
        .sidebar { background: #102033; color: #fff; padding: 22px 16px; }
        .brand { font-size: 22px; font-weight: 700; margin-bottom: 24px; }
        .nav { display: grid; gap: 8px; }
        .nav a, .nav button, .logout { width: 100%; display: block; padding: 11px 12px; border-radius: 6px; color: #d9e6f5; background: transparent; border: 0; text-align: left; font: inherit; cursor: pointer; }
        .nav a.active, .nav button.active, .nav a:hover, .nav button:hover, .logout:hover { background: #1d3552; color: #fff; }
        .content { padding: 24px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; gap: 16px; }
        .title { margin: 0; font-size: 28px; }
        .muted { color: #68758a; }
        .grid { display: grid; gap: 16px; }
        .stats { grid-template-columns: repeat(5, minmax(140px, 1fr)); }
        .panel { background: #fff; border: 1px solid #e3e8f0; border-radius: 8px; padding: 18px; box-shadow: 0 8px 24px rgba(16, 32, 51, .05); }
        .stat strong { display: block; font-size: 26px; margin-top: 8px; }
        .row { display: grid; grid-template-columns: repeat(6, minmax(120px, 1fr)); gap: 10px; align-items: end; }
        .row-4 { grid-template-columns: repeat(4, minmax(120px, 1fr)); }
        .row-3 { grid-template-columns: repeat(3, minmax(120px, 1fr)); }
        .row-2 { grid-template-columns: repeat(2, minmax(220px, 1fr)); }
        label { display: grid; gap: 6px; font-size: 13px; color: #41506a; }
        input, select, textarea { width: 100%; border: 1px solid #cfd8e3; border-radius: 6px; padding: 10px; font: inherit; background: #fff; }
        textarea { min-height: 42px; resize: vertical; }
        button, .btn { border: 0; border-radius: 6px; padding: 10px 13px; font: inherit; cursor: pointer; background: #1f6feb; color: #fff; display: inline-flex; align-items: center; justify-content: center; gap: 6px; }
        .btn-danger { background: #c83232; }
        .btn-secondary { background: #44546a; }
        .btn-light { background: #eef3f8; color: #162033; }
        .link { color: #1f6feb; font-weight: 600; }
        .quick { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px; }
        .page-shortcuts { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 12px; }
        .info { display: grid; grid-template-columns: repeat(4, minmax(140px, 1fr)); gap: 12px; }
        .info div { background: #f8fafc; border: 1px solid #e8edf4; border-radius: 6px; padding: 12px; }
        .info span { display: block; color: #68758a; font-size: 13px; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { padding: 11px 9px; border-bottom: 1px solid #e8edf4; text-align: left; vertical-align: top; }
        th { font-size: 13px; color: #53627a; background: #f8fafc; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .alert { padding: 12px 14px; border-radius: 6px; margin-bottom: 16px; }
        .success { background: #e8f7ee; color: #146c2e; border: 1px solid #bfe9cb; }
        .error { background: #fff1f1; color: #a22121; border: 1px solid #f2c0c0; }
        .pagination { margin-top: 14px; }
        @media (max-width: 900px) {
            .app { grid-template-columns: 1fr; }
            .sidebar { position: static; }
            .stats, .row, .row-4, .row-3, .row-2 { grid-template-columns: 1fr; }
            .info { grid-template-columns: 1fr; }
            .content { padding: 16px; }
        }
    </style>
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="brand">Usine Jus</div>
        <nav class="nav">
            <button type="button" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" data-go="{{ route('dashboard') }}">Dashboard</button>
            <button type="button" class="{{ request()->routeIs('products.*') ? 'active' : '' }}" data-go="{{ route('products.index') }}">Produits</button>
            <button type="button" class="{{ request()->routeIs('raw-materials.*') ? 'active' : '' }}" data-go="{{ route('raw-materials.index') }}">Matieres premieres</button>
            <button type="button" class="{{ request()->routeIs('productions.*') ? 'active' : '' }}" data-go="{{ route('productions.index') }}">Productions</button>
            <button type="button" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}" data-go="{{ route('orders.index') }}">Commandes</button>
            <button type="button" class="{{ request()->routeIs('clients.*') ? 'active' : '' }}" data-go="{{ route('clients.index') }}">Clients</button>
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button class="logout" type="submit">Deconnexion</button>
            </form>
        </nav>
    </aside>
    <main class="content">
        <div class="topbar">
            <div>
                <h1 class="title">@yield('title')</h1>
                <div class="muted">{{ auth()->user()->name ?? '' }} - {{ auth()->user()->role ?? '' }}</div>
                <div class="page-shortcuts">
                    <button type="button" class="btn btn-light" data-go="{{ route('dashboard') }}">Dashboard</button>
                    <button type="button" class="btn btn-light" data-go="{{ route('products.index') }}">Produits</button>
                    <button type="button" class="btn btn-light" data-go="{{ route('raw-materials.index') }}">Matieres</button>
                    <button type="button" class="btn btn-light" data-go="{{ route('productions.index') }}">Productions</button>
                    <button type="button" class="btn btn-light" data-go="{{ route('orders.index') }}">Commandes</button>
                    <button type="button" class="btn btn-light" data-go="{{ route('clients.index') }}">Clients</button>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>
</div>
<script>
    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-go]');
        if (!button) {
            return;
        }

        window.location.assign(button.dataset.go);
    });
</script>
</body>
</html>
