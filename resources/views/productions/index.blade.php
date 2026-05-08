@extends('layouts.app')

@section('title', 'Productions')

@section('content')
<section class="panel">
    <h2>Nouvelle production</h2>
    <form method="post" action="{{ route('productions.store') }}" class="grid">
        @csrf
        <div class="row-4">
            <label>Produit fabrique
                <select name="product_id" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} - stock {{ $product->stock_quantity }}</option>
                    @endforeach
                </select>
            </label>
            <label>Employe
                <select name="employee_id">
                    <option value="">Non renseigne</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Date <input name="production_date" type="date" value="{{ now()->toDateString() }}" required></label>
            <label>Quantite produite <input name="quantity_produced" type="number" step="0.01" value="1" required></label>
        </div>
        <h3>Matieres consommees</h3>
        @for($i = 0; $i < 3; $i++)
            <div class="row-3">
                <label>Matiere {{ $i + 1 }}
                    <select name="materials[{{ $i }}][raw_material_id]" {{ $i === 0 ? 'required' : '' }}>
                        <option value="">Choisir</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}">{{ $material->name }} - stock {{ $material->stock_quantity }} {{ $material->unit }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Quantite utilisee
                    <input name="materials[{{ $i }}][quantity_used]" type="number" step="0.01" value="{{ $i === 0 ? '1' : '' }}" {{ $i === 0 ? 'required' : '' }}>
                </label>
            </div>
        @endfor
        <label>Notes <textarea name="notes"></textarea></label>
        <button type="submit">Enregistrer la production</button>
    </form>
</section>

<section class="panel" style="margin-top:16px;">
    <h2>Historique</h2>
    <table>
        <thead><tr><th>Date</th><th>Produit</th><th>Quantite</th><th>Matieres</th><th>Action</th></tr></thead>
        <tbody>
        @foreach($productions as $production)
            <tr>
                <td>{{ $production->production_date }}</td>
                <td><a class="link" href="{{ route('products.show', $production->product) }}">{{ $production->product->name }}</a></td>
                <td>{{ $production->quantity_produced }}</td>
                <td>
                    @foreach($production->details as $detail)
                        <div><a class="link" href="{{ route('raw-materials.show', $detail->rawMaterial) }}">{{ $detail->rawMaterial->name }}</a> : {{ $detail->quantity_used }}</div>
                    @endforeach
                </td>
                <td>
                    <div class="actions">
                    <button type="button" class="btn btn-light" data-go="{{ route('productions.show', $production) }}">Voir</button>
                    <form method="post" action="{{ route('productions.destroy', $production) }}">
                        @csrf
                        @method('delete')
                        <button class="btn-danger" type="submit" onclick="return confirm('Annuler cette production et restaurer les stocks ?')">Annuler</button>
                    </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $productions->links() }}</div>
</section>
@endsection
