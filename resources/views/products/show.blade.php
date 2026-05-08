@extends('layouts.app')

@section('title', 'Produit : '.$product->name)

@section('content')
<section class="panel">
    <div class="quick">
        <a class="btn btn-light" href="{{ route('products.index') }}">Retour produits</a>
        <a class="btn" href="{{ route('productions.index') }}">Produire ce type de produit</a>
        <a class="btn" href="{{ route('orders.index') }}">Commander ce produit</a>
    </div>
    <div class="info" style="margin-top:16px;">
        <div><span>SKU</span>{{ $product->sku }}</div>
        <div><span>Stock</span>{{ $product->stock_quantity }} {{ $product->unit }}</div>
        <div><span>Prix vente</span>{{ number_format($product->sale_price, 0, ',', ' ') }}</div>
        <div><span>Seuil alerte</span>{{ $product->alert_threshold }}</div>
    </div>
</section>

<section class="grid row-2" style="margin-top:16px;">
    <div class="panel">
        <h2>Productions</h2>
        @forelse($product->productions as $production)
            <p><a class="link" href="{{ route('productions.show', $production) }}">Production #{{ $production->id }}</a> - {{ $production->quantity_produced }} - {{ $production->production_date }}</p>
        @empty
            <p class="muted">Aucune production.</p>
        @endforelse
    </div>
    <div class="panel">
        <h2>Ventes</h2>
        @forelse($product->orderDetails as $detail)
            <p><a class="link" href="{{ route('orders.show', $detail->order) }}">Commande #{{ $detail->order_id }}</a> - {{ $detail->quantity }} pour {{ $detail->order->client->name }}</p>
        @empty
            <p class="muted">Aucune vente.</p>
        @endforelse
    </div>
</section>
@endsection
