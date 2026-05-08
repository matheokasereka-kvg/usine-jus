<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Usine Jus') }}</title>
    <style>
        :root {
            --bg: #f4f7f2;
            --surface: #ffffff;
            --surface-soft: #f8fbf5;
            --ink: #172016;
            --muted: #65725f;
            --line: #dde8d7;
            --brand: #2f7d32;
            --brand-dark: #14532d;
            --brand-soft: #e8f5df;
            --orange: #f59e0b;
            --danger: #dc2626;
            --shadow: 0 18px 50px rgba(31, 76, 28, .10);
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background: radial-gradient(circle at top left, #fff7d6 0, transparent 34%), linear-gradient(135deg, #f7fbf1 0%, #eef7ec 45%, #f8fafc 100%); color: var(--ink); }
        a { color: inherit; text-decoration: none; }
        h2, h3 { margin-top: 0; color: #20351d; }
        .app { min-height: 100vh; display: grid; grid-template-columns: 280px 1fr; }
        .sidebar { position: sticky; top: 0; min-height: 100vh; background: linear-gradient(180deg, #12351f 0%, #0f2418 100%); color: #fff; padding: 26px 18px; overflow: hidden; }
        .sidebar::after { content: ""; position: absolute; width: 180px; height: 180px; right: -70px; bottom: 40px; border-radius: 999px; background: rgba(245, 158, 11, .18); pointer-events: none; }
        .brand { position: relative; font-size: 24px; font-weight: 900; margin-bottom: 28px; letter-spacing: -.04em; display: flex; align-items: center; gap: 10px; }
        .brand::before { content: "🍹"; display: grid; place-items: center; width: 42px; height: 42px; border-radius: 14px; background: rgba(255, 255, 255, .14); }
        .nav { position: relative; display: grid; gap: 9px; z-index: 1; }
        .nav a, .nav button, .logout { width: 100%; display: flex; align-items: center; gap: 10px; padding: 12px 14px; border-radius: 14px; color: #d9f4df; background: transparent; border: 1px solid transparent; text-align: left; font: inherit; cursor: pointer; transition: .2s ease; }
        .nav a.active, .nav button.active, .nav a:hover, .nav button:hover, .logout:hover { background: rgba(255, 255, 255, .12); border-color: rgba(255, 255, 255, .14); color: #fff; transform: translateX(3px); }
        .content { padding: 28px; }
        .topbar { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; gap: 16px; }
        .eyebrow { color: var(--brand); font-size: 12px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; margin-bottom: 6px; }
        .title { margin: 0; font-size: clamp(30px, 4vw, 46px); line-height: 1; letter-spacing: -.06em; }
        .muted { color: var(--muted); }
        .grid { display: grid; gap: 18px; }
        .stats { grid-template-columns: repeat(5, minmax(140px, 1fr)); }
        .panel { background: rgba(255, 255, 255, .9); border: 1px solid rgba(221, 232, 215, .9); border-radius: 24px; padding: 22px; box-shadow: var(--shadow); backdrop-filter: blur(14px); }
        .stat { position: relative; min-height: 128px; overflow: hidden; transition: transform .2s ease, box-shadow .2s ease; }
        .stat:hover { transform: translateY(-3px); box-shadow: 0 24px 60px rgba(31, 76, 28, .14); }
        .stat::after { content: ""; position: absolute; width: 96px; height: 96px; right: -28px; bottom: -32px; border-radius: 999px; background: var(--brand-soft); }
        .stat span { color: var(--muted); font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; }
        .stat strong { display: block; font-size: 34px; margin-top: 12px; letter-spacing: -.05em; }
        .row { display: grid; grid-template-columns: repeat(6, minmax(120px, 1fr)); gap: 12px; align-items: end; }
        .row-4 { display: grid; grid-template-columns: repeat(4, minmax(120px, 1fr)); gap: 12px; align-items: end; }
        .row-3 { display: grid; grid-template-columns: repeat(3, minmax(120px, 1fr)); gap: 12px; }
        .row-2 { display: grid; grid-template-columns: repeat(2, minmax(220px, 1fr)); gap: 18px; }
        label { display: grid; gap: 7px; font-size: 13px; color: #41533d; font-weight: 700; }
        input, select, textarea { width: 100%; border: 1px solid #d5e2ce; border-radius: 13px; padding: 11px 12px; font: inherit; background: #fff; color: var(--ink); outline: none; transition: .2s ease; }
        input:focus, select:focus, textarea:focus { border-color: var(--brand); box-shadow: 0 0 0 4px rgba(47, 125, 50, .12); }
        textarea { min-height: 42px; resize: vertical; }
        button, .btn { border: 0; border-radius: 13px; padding: 11px 15px; font: inherit; font-weight: 800; cursor: pointer; background: linear-gradient(135deg, var(--brand) 0%, #54a24b 100%); color: #fff; display: inline-flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 0 10px 24px rgba(47, 125, 50, .18); }
        .btn-danger { background: linear-gradient(135deg, var(--danger) 0%, #ef4444 100%); box-shadow: 0 10px 24px rgba(220, 38, 38, .16); }
        .btn-secondary { background: #44546a; }
        .btn-light { background: #edf7e8; color: #1d3a1a; box-shadow: none; }
        .link { color: var(--brand); font-weight: 800; }
        .quick { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 14px; }
        .page-shortcuts { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 16px; }
        .info { display: grid; grid-template-columns: repeat(4, minmax(140px, 1fr)); gap: 12px; }
        .info div { background: var(--surface-soft); border: 1px solid var(--line); border-radius: 16px; padding: 14px; }
        .info span { display: block; color: var(--muted); font-size: 13px; margin-bottom: 5px; }
        .table-wrap { width: 100%; overflow-x: auto; border: 1px solid var(--line); border-radius: 20px; background: #fff; }
        table { width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 14px; overflow: hidden; }
        .table-wrap table { margin-top: 0; }
        th, td { padding: 14px 13px; border-bottom: 1px solid #e8f0e3; text-align: left; vertical-align: top; }
        th { position: sticky; top: 0; font-size: 12px; color: #527047; background: #f2f8ed; text-transform: uppercase; letter-spacing: .08em; z-index: 1; }
        tbody tr { transition: background .2s ease, transform .2s ease; }
        tbody tr:hover { background: #fbfdf8; }
        tbody tr:last-child td { border-bottom: 0; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .badge { display: inline-flex; align-items: center; border-radius: 999px; padding: 5px 10px; background: var(--brand-soft); color: var(--brand-dark); font-size: 12px; font-weight: 900; }
        .badge-warning { background: #fff3d7; color: #92400e; }
        .alert { padding: 13px 15px; border-radius: 16px; margin-bottom: 16px; }
        .success { background: #e8f7ee; color: #146c2e; border: 1px solid #bfe9cb; }
        .error { background: #fff1f1; color: #a22121; border: 1px solid #f2c0c0; }
        .pagination { margin-top: 14px; }
        @media (max-width: 1000px) {
            .app { grid-template-columns: 1fr; }
            .sidebar { position: static; min-height: auto; }
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
                <div class="eyebrow">Pilotage production</div>
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
