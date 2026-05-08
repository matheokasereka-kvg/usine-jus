@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="grid stats">
    <a class="panel stat" href="{{ route('products.index') }}"><span>Produits</span><strong>{{ $productsCount }}</strong></a>
    <a class="panel stat" href="{{ route('raw-materials.index') }}"><span>Matieres</span><strong>{{ $materialsCount }}</strong></a>
    <a class="panel stat" href="{{ route('productions.index') }}"><span>Productions</span><strong>{{ $productionsCount }}</strong></a>
    <a class="panel stat" href="{{ route('orders.index') }}"><span>Commandes</span><strong>{{ $ordersCount }}</strong></a>
    <div class="panel stat"><span>Ventes</span><strong>{{ number_format($salesTotal, 0, ',', ' ') }}</strong></div>
</section>

<section class="grid row-2" style="margin-top:18px;">
    <div class="panel">
        <h2>Sante de l'usine</h2>
        <div class="info">
            <div><span>Valeur stock produits</span>{{ number_format($productStockValue, 0, ',', ' ') }} XAF</div>
            <div><span>Cout stock matieres</span>{{ number_format($materialStockCost, 0, ',', ' ') }} XAF</div>
            <div><span>Clients actifs</span>{{ $clientsCount }}</div>
            <div><span>Alertes stock</span>{{ $lowProducts->count() + $lowMaterials->count() }}</div>
        </div>
    </div>
    <div class="panel">
        <h2>Navigation rapide</h2>
        <p class="muted">Accedez directement aux operations les plus utilisees.</p>
        <div class="quick">
            <a class="btn" href="{{ route('products.index') }}">Gerer les produits</a>
            <a class="btn" href="{{ route('raw-materials.index') }}">Gerer les matieres</a>
            <a class="btn" href="{{ route('productions.index') }}">Nouvelle production</a>
            <a class="btn" href="{{ route('orders.index') }}">Nouvelle commande</a>
            <a class="btn btn-light" href="{{ route('clients.index') }}">Clients</a>
        </div>
    </div>
</section>

<section class="grid row-3" style="margin-top:18px;">
    <div class="panel">
        <h2>Stocks produits bas</h2>
        @forelse($lowProducts as $product)
            <p><span class="badge badge-warning">Alerte</span> <a class="link" href="{{ route('products.show', $product) }}">{{ $product->name }}</a> : {{ $product->stock_quantity }} {{ $product->unit }}</p>
        @empty
            <p class="muted">Aucune alerte produit.</p>
        @endforelse
    </div>
    <div class="panel">
        <h2>Stocks matieres bas</h2>
        @forelse($lowMaterials as $material)
            <p><span class="badge badge-warning">Alerte</span> <a class="link" href="{{ route('raw-materials.show', $material) }}">{{ $material->name }}</a> : {{ $material->stock_quantity }} {{ $material->unit }}</p>
        @empty
            <p class="muted">Aucune alerte matiere.</p>
        @endforelse
    </div>
    <div class="panel">
        <h2>Statuts commandes</h2>
        @forelse($ordersByStatus as $status => $total)
            <p><span class="badge">{{ $status }}</span> {{ $total }} commande(s)</p>
        @empty
            <p class="muted">Aucune commande.</p>
        @endforelse
    </div>
</section>

<section class="panel" style="margin-top:18px;">
    <h2>Dernieres commandes</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Commande</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Produits</th>
                    <th>Statut</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestOrders as $order)
                    <tr>
                        <td><a class="link" href="{{ route('orders.show', $order) }}">#{{ $order->id }}</a></td>
                        <td>{{ $order->order_date }}</td>
                        <td><a class="link" href="{{ route('clients.show', $order->client) }}">{{ $order->client->name }}</a></td>
                        <td>
                            @foreach($order->details as $detail)
                                <div>{{ $detail->product->name }} x {{ $detail->quantity }}</div>
                            @endforeach
                        </td>
                        <td><span class="badge">{{ $order->status }}</span></td>
                        <td><strong>{{ number_format($order->total_amount, 0, ',', ' ') }} XAF</strong></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">Aucune commande.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
