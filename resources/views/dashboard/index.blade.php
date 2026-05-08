@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="grid stats">
    <a class="panel stat" href="{{ route('products.index') }}">Produits<strong>{{ $productsCount }}</strong></a>
    <a class="panel stat" href="{{ route('raw-materials.index') }}">Matieres<strong>{{ $materialsCount }}</strong></a>
    <a class="panel stat" href="{{ route('productions.index') }}">Productions<strong>{{ $productionsCount }}</strong></a>
    <a class="panel stat" href="{{ route('orders.index') }}">Commandes<strong>{{ $ordersCount }}</strong></a>
    <div class="panel stat">Ventes<strong>{{ number_format($salesTotal, 0, ',', ' ') }}</strong></div>
</section>

<section class="panel" style="margin-top:16px;">
    <h2>Navigation rapide</h2>
    <div class="quick">
        <a class="btn" href="{{ route('products.index') }}">Gerer les produits</a>
        <a class="btn" href="{{ route('raw-materials.index') }}">Gerer les matieres</a>
        <a class="btn" href="{{ route('productions.index') }}">Nouvelle production</a>
        <a class="btn" href="{{ route('orders.index') }}">Nouvelle commande</a>
        <a class="btn" href="{{ route('clients.index') }}">Gerer les clients</a>
    </div>
</section>

<section class="grid row-3" style="margin-top:16px;">
    <div class="panel">
        <h2>Stocks produits bas</h2>
        @forelse($lowProducts as $product)
            <p><a class="link" href="{{ route('products.show', $product) }}">{{ $product->name }}</a> : {{ $product->stock_quantity }} {{ $product->unit }}</p>
        @empty
            <p class="muted">Aucune alerte produit.</p>
        @endforelse
    </div>
    <div class="panel">
        <h2>Stocks matieres bas</h2>
        @forelse($lowMaterials as $material)
            <p><a class="link" href="{{ route('raw-materials.show', $material) }}">{{ $material->name }}</a> : {{ $material->stock_quantity }} {{ $material->unit }}</p>
        @empty
            <p class="muted">Aucune alerte matiere.</p>
        @endforelse
    </div>
    <div class="panel">
        <h2>Dernieres commandes</h2>
        @forelse($latestOrders as $order)
            <p><a class="link" href="{{ route('orders.show', $order) }}">#{{ $order->id }}</a> - <a class="link" href="{{ route('clients.show', $order->client) }}">{{ $order->client->name }}</a> - {{ number_format($order->total_amount, 0, ',', ' ') }}</p>
        @empty
            <p class="muted">Aucune commande.</p>
        @endforelse
    </div>
</section>
@endsection
