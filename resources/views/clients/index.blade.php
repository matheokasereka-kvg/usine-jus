@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<section class="panel">
    <h2>Ajouter un client</h2>
    <form method="post" action="{{ route('clients.store') }}" class="row-4">
        @csrf
        <label>Nom <input name="name" required></label>
        <label>Telephone <input name="phone"></label>
        <label>Email <input name="email" type="email"></label>
        <label>Adresse <input name="address"></label>
        <button type="submit">Ajouter</button>
    </form>
</section>

<section class="panel" style="margin-top:16px;">
    <h2>Liste des clients</h2>
    <table>
        <thead><tr><th>Nom</th><th>Telephone</th><th>Email</th><th>Adresse</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($clients as $client)
            <tr>
                <td><input form="client-update-{{ $client->id }}" name="name" value="{{ $client->name }}" required></td>
                <td><input form="client-update-{{ $client->id }}" name="phone" value="{{ $client->phone }}"></td>
                <td><input form="client-update-{{ $client->id }}" name="email" type="email" value="{{ $client->email }}"></td>
                <td><input form="client-update-{{ $client->id }}" name="address" value="{{ $client->address }}"></td>
                <td class="actions">
                    <button type="button" class="btn btn-light" data-go="{{ route('clients.show', $client) }}">Voir</button>
                    <form id="client-update-{{ $client->id }}" method="post" action="{{ route('clients.update', $client) }}">
                        @csrf
                        @method('put')
                        <button type="submit">Modifier</button>
                    </form>
                    <form method="post" action="{{ route('clients.destroy', $client) }}">
                        @csrf
                        @method('delete')
                        <button class="btn-danger" type="submit" onclick="return confirm('Supprimer ce client ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $clients->links() }}</div>
</section>
@endsection
