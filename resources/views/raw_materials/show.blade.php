@extends('layouts.app')

@section('title', 'Matiere : '.$material->name)

@section('content')
<section class="panel">
    <div class="quick">
        <a class="btn btn-light" href="{{ route('raw-materials.index') }}">Retour matieres</a>
        <a class="btn" href="{{ route('productions.index') }}">Utiliser en production</a>
    </div>
    <div class="info" style="margin-top:16px;">
        <div><span>Fournisseur</span>{{ $material->supplier->name ?? '-' }}</div>
        <div><span>Stock</span>{{ $material->stock_quantity }} {{ $material->unit }}</div>
        <div><span>Cout unitaire</span>{{ number_format($material->unit_cost, 0, ',', ' ') }}</div>
        <div><span>Seuil alerte</span>{{ $material->alert_threshold }}</div>
    </div>
</section>

<section class="panel" style="margin-top:16px;">
    <h2>Utilisation en production</h2>
    @forelse($material->productionDetails as $detail)
        <p><a class="link" href="{{ route('productions.show', $detail->production) }}">Production #{{ $detail->production_id }}</a> - {{ $detail->quantity_used }} utilise pour {{ $detail->production->product->name }}</p>
    @empty
        <p class="muted">Aucune utilisation.</p>
    @endforelse
</section>
@endsection
