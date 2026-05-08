@extends('layouts.app')

@section('title', 'Produits')

@section('content')
<section class="panel">
    <h2>Ajouter un produit</h2>
    <form method="post" action="{{ route('products.store') }}" class="row">
        @csrf
        <label>Nom <input name="name" required></label>
        <label>SKU <input name="sku" required></label>
        <label>Unite <input name="unit" value="bouteille" required></label>
        <label>Stock <input name="stock_quantity" type="number" step="0.01" value="0" required></label>
        <label>Prix vente <input name="sale_price" type="number" step="0.01" required></label>
        <label>Seuil alerte <input name="alert_threshold" type="number" step="0.01" value="0" required></label>
        <button type="submit">Ajouter</button>
    </form>
</section>

<section class="panel" style="margin-top:16px;">
    <h2>Liste des produits</h2>
    <table>
        <thead><tr><th>Nom</th><th>SKU</th><th>Stock</th><th>Prix</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td><input form="product-update-{{ $product->id }}" name="name" value="{{ $product->name }}" required></td>
                <td><input form="product-update-{{ $product->id }}" name="sku" value="{{ $product->sku }}" required></td>
                <td>
                    <input form="product-update-{{ $product->id }}" name="unit" value="{{ $product->unit }}" required style="margin-bottom:6px;">
                    <input form="product-update-{{ $product->id }}" name="stock_quantity" type="number" step="0.01" value="{{ $product->stock_quantity }}" required>
                </td>
                <td>
                    <input form="product-update-{{ $product->id }}" name="sale_price" type="number" step="0.01" value="{{ $product->sale_price }}" required style="margin-bottom:6px;">
                    <input form="product-update-{{ $product->id }}" name="alert_threshold" type="number" step="0.01" value="{{ $product->alert_threshold }}" required>
                </td>
                <td class="actions">
                    <button type="button" class="btn btn-light" data-go="{{ route('products.show', $product) }}">Voir</button>
                    <form id="product-update-{{ $product->id }}" method="post" action="{{ route('products.update', $product) }}">
                        @csrf
                        @method('put')
                        <button type="submit">Modifier</button>
                    </form>
                    <form method="post" action="{{ route('products.destroy', $product) }}">
                        @csrf
                        @method('delete')
                        <button class="btn-danger" type="submit" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $products->links() }}</div>
</section>
@endsection
