@extends('layouts.app')

@section('title', 'Commande #'.$order->id)

@section('content')
<section class="panel">
    <div class="quick">
        <a class="btn btn-light" href="{{ route('orders.index') }}">Retour commandes</a>
        <a class="btn" href="{{ route('clients.show', $order->client) }}">Voir le client</a>
    </div>
    <div class="info" style="margin-top:16px;">
        <div><span>Date</span>{{ $order->order_date }}</div>
        <div><span>Client</span>{{ $order->client->name }}</div>
        <div><span>Statut</span>{{ $order->status }}</div>
        <div><span>Total</span>{{ number_format($order->total_amount, 0, ',', ' ') }}</div>
    </div>
</section>

<section class="panel" style="margin-top:16px;">
    <h2>Produits commandes</h2>
    <table>
        <thead><tr><th>Produit</th><th>Quantite</th><th>Prix unitaire</th><th>Total ligne</th><th>Action</th></tr></thead>
        <tbody>
        @foreach($order->details as $detail)
            <tr>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ number_format($detail->unit_price, 0, ',', ' ') }}</td>
                <td>{{ number_format($detail->line_total, 0, ',', ' ') }}</td>
                <td><a class="btn btn-light" href="{{ route('products.show', $detail->product) }}">Voir produit</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</section>
@endsection
