@extends('layouts.app')

@section('title', 'Production #'.$production->id)

@section('content')
<section class="panel">
    <div class="quick">
        <a class="btn btn-light" href="{{ route('productions.index') }}">Retour productions</a>
        <a class="btn" href="{{ route('products.show', $production->product) }}">Voir le produit</a>
    </div>
    <div class="info" style="margin-top:16px;">
        <div><span>Date</span>{{ $production->production_date }}</div>
        <div><span>Produit</span>{{ $production->product->name }}</div>
        <div><span>Quantite produite</span>{{ $production->quantity_produced }}</div>
        <div><span>Statut</span>{{ $production->status }}</div>
    </div>
</section>

<section class="panel" style="margin-top:16px;">
    <h2>Matieres consommees</h2>
    <table>
        <thead><tr><th>Matiere</th><th>Quantite</th><th>Cout unitaire</th><th>Action</th></tr></thead>
        <tbody>
        @foreach($production->details as $detail)
            <tr>
                <td>{{ $detail->rawMaterial->name }}</td>
                <td>{{ $detail->quantity_used }}</td>
                <td>{{ number_format($detail->unit_cost, 0, ',', ' ') }}</td>
                <td><a class="btn btn-light" href="{{ route('raw-materials.show', $detail->rawMaterial) }}">Voir matiere</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</section>
@endsection
