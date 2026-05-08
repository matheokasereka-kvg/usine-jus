@extends('layouts.app')

@section('title', 'Matieres premieres')

@section('content')
<section class="panel">
    <h2>Ajouter une matiere premiere</h2>
    <form method="post" action="{{ route('raw-materials.store') }}" class="row">
        @csrf
        <label>Nom <input name="name" required></label>
        <label>Fournisseur
            <select name="supplier_id">
                <option value="">Aucun</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </label>
        <label>Unite <input name="unit" value="kg" required></label>
        <label>Stock <input name="stock_quantity" type="number" step="0.01" value="0" required></label>
        <label>Cout unitaire <input name="unit_cost" type="number" step="0.01" required></label>
        <label>Seuil alerte <input name="alert_threshold" type="number" step="0.01" value="0" required></label>
        <button type="submit">Ajouter</button>
    </form>
</section>

<section class="panel" style="margin-top:16px;">
    <h2>Liste des matieres premieres</h2>
    <table>
        <thead><tr><th>Nom</th><th>Fournisseur</th><th>Stock</th><th>Cout</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($materials as $material)
            <tr>
                <td><input form="material-update-{{ $material->id }}" name="name" value="{{ $material->name }}" required></td>
                <td>
                    <select form="material-update-{{ $material->id }}" name="supplier_id">
                        <option value="">Aucun</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected($material->supplier_id === $supplier->id)>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input form="material-update-{{ $material->id }}" name="unit" value="{{ $material->unit }}" required style="margin-bottom:6px;">
                    <input form="material-update-{{ $material->id }}" name="stock_quantity" type="number" step="0.01" value="{{ $material->stock_quantity }}" required>
                </td>
                <td>
                    <input form="material-update-{{ $material->id }}" name="unit_cost" type="number" step="0.01" value="{{ $material->unit_cost }}" required style="margin-bottom:6px;">
                    <input form="material-update-{{ $material->id }}" name="alert_threshold" type="number" step="0.01" value="{{ $material->alert_threshold }}" required>
                </td>
                <td class="actions">
                    <button type="button" class="btn btn-light" data-go="{{ route('raw-materials.show', $material) }}">Voir</button>
                    <form id="material-update-{{ $material->id }}" method="post" action="{{ route('raw-materials.update', $material) }}">
                        @csrf
                        @method('put')
                        <button type="submit">Modifier</button>
                    </form>
                    <form method="post" action="{{ route('raw-materials.destroy', $material) }}">
                        @csrf
                        @method('delete')
                        <button class="btn-danger" type="submit" onclick="return confirm('Supprimer cette matiere ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $materials->links() }}</div>
</section>
@endsection
