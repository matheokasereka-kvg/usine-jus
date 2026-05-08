@extends('layouts.app')

@section('title', 'Commandes')

@section('content')
<section class="panel">
    <h2>Nouvelle commande</h2>
    <form method="post" action="{{ route('orders.store') }}" class="grid">
        @csrf
        <div class="row-3">
            <label>Client
                <select name="client_id" required>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Date <input name="order_date" type="date" value="{{ now()->toDateString() }}" required></label>
        </div>
        <h3>Produits commandes</h3>
        @for($i = 0; $i < 3; $i++)
            <div class="row-3">
                <label>Produit {{ $i + 1 }}
                    <select name="items[{{ $i }}][product_id]" {{ $i === 0 ? 'required' : '' }}>
                        <option value="">Choisir</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} - {{ number_format($product->sale_price, 0, ',', ' ') }} - stock {{ $product->stock_quantity }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Quantite
                    <input name="items[{{ $i }}][quantity]" type="number" step="0.01" value="{{ $i === 0 ? '1' : '' }}" {{ $i === 0 ? 'required' : '' }}>
                </label>
            </div>
        @endfor
        <button type="submit">Enregistrer la commande</button>
    </form>
</section>

<section class="panel" style="margin-top:16px;">
    <h2>Historique</h2>
    <table>
        <thead><tr><th>Date</th><th>Client</th><th>Produits</th><th>Total</th><th>Action</th></tr></thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->order_date }}</td>
                <td><a class="link" href="{{ route('clients.show', $order->client) }}">{{ $order->client->name }}</a></td>
                <td>
                    @foreach($order->details as $detail)
                        <div><a class="link" href="{{ route('products.show', $detail->product) }}">{{ $detail->product->name }}</a> : {{ $detail->quantity }} x {{ number_format($detail->unit_price, 0, ',', ' ') }}</div>
                    @endforeach
                </td>
                <td>{{ number_format($order->total_amount, 0, ',', ' ') }}</td>
                <td>
                    <div class="actions">
                    <button type="button" class="btn btn-light" data-go="{{ route('orders.show', $order) }}">Voir</button>
                    <form method="post" action="{{ route('orders.destroy', $order) }}">
                        @csrf
                        @method('delete')
                        <button class="btn-danger" type="submit" onclick="return confirm('Annuler cette commande et restaurer le stock ?')">Annuler</button>
                    </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $orders->links() }}</div>
</section>
@endsection
