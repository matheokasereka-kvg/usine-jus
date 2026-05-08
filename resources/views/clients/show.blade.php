@extends('layouts.app')

@section('title', 'Client : '.$client->name)

@section('content')
<section class="panel">
    <div class="quick">
        <a class="btn btn-light" href="{{ route('clients.index') }}">Retour clients</a>
        <a class="btn" href="{{ route('orders.index') }}">Creer une commande</a>
    </div>
    <div class="info" style="margin-top:16px;">
        <div><span>Nom</span>{{ $client->name }}</div>
        <div><span>Telephone</span>{{ $client->phone ?: '-' }}</div>
        <div><span>Email</span>{{ $client->email ?: '-' }}</div>
        <div><span>Adresse</span>{{ $client->address ?: '-' }}</div>
    </div>
</section>

<section class="panel" style="margin-top:16px;">
    <h2>Commandes du client</h2>
    <table>
        <thead><tr><th>Date</th><th>Produits</th><th>Total</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($client->orders as $order)
            <tr>
                <td>{{ $order->order_date }}</td>
                <td>
                    @foreach($order->details as $detail)
                        <div><a class="link" href="{{ route('products.show', $detail->product) }}">{{ $detail->product->name }}</a> : {{ $detail->quantity }}</div>
                    @endforeach
                </td>
                <td>{{ number_format($order->total_amount, 0, ',', ' ') }}</td>
                <td><a class="btn btn-light" href="{{ route('orders.show', $order) }}">Voir commande</a></td>
            </tr>
        @empty
            <tr><td colspan="4" class="muted">Aucune commande pour ce client.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
@endsection
