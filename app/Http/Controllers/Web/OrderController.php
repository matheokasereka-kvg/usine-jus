<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index', [
            'orders' => Order::with('client', 'details.product')->latest()->paginate(10),
            'clients' => Client::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function show(Order $order)
    {
        return view('orders.show', [
            'order' => $order->load('client', 'details.product'),
        ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'items' => collect($request->input('items', []))
                ->filter(fn ($line) => filled($line['product_id'] ?? null) || filled($line['quantity'] ?? null))
                ->values()
                ->all(),
        ]);

        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'order_date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['items'] as $line) {
                $product = Product::lockForUpdate()->find($line['product_id']);
                if ($product->stock_quantity < $line['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => "Stock insuffisant pour {$product->name}.",
                    ]);
                }
            }

            $order = Order::create([
                'client_id' => $data['client_id'],
                'order_date' => $data['order_date'],
                'status' => 'validee',
                'total_amount' => 0,
            ]);
            $total = 0;

            foreach ($data['items'] as $line) {
                $product = Product::lockForUpdate()->find($line['product_id']);
                $lineTotal = $product->sale_price * $line['quantity'];
                $product->decrement('stock_quantity', $line['quantity']);
                $order->details()->create([
                    'product_id' => $product->id,
                    'quantity' => $line['quantity'],
                    'unit_price' => $product->sale_price,
                    'line_total' => $lineTotal,
                ]);
                $total += $lineTotal;
            }

            $order->update(['total_amount' => $total]);
        });

        return back()->with('success', 'Commande enregistree et stock produit mis a jour.');
    }

    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->load('details');
            foreach ($order->details as $detail) {
                Product::lockForUpdate()->find($detail->product_id)->increment('stock_quantity', $detail->quantity);
            }
            $order->delete();
        });

        return back()->with('success', 'Commande annulee et stock restaure.');
    }
}
