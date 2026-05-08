<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return view('clients.index', ['clients' => Client::latest()->paginate(10)]);
    }

    public function show(Client $client)
    {
        return view('clients.show', [
            'client' => $client->load('orders.details.product'),
        ]);
    }

    public function store(Request $request)
    {
        Client::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
        ]));

        return back()->with('success', 'Client ajoute.');
    }

    public function update(Request $request, Client $client)
    {
        $client->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
        ]));

        return back()->with('success', 'Client modifie.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return back()->with('success', 'Client supprime.');
    }
}
