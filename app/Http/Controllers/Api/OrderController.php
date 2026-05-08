<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('client', 'details.product')->latest()->paginate(20);
    }

    public function show(Order $order)
    {
        return $order->load('client', 'details.product');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'order_date' => ['required', 'date'],
            'status' => ['nullable', 'string', 'max:60'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
        ]);

        return DB::transaction(function () use ($data) {
            foreach ($data['items'] as $line) {
                $product = Product::lockForUpdate()->find($line['product_id']);
                if ($product->stock_quantity < $line['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => "Stock insuffisant pour {$product->name}.",
                    ]);
                }
            }

            $order = Order::create(collect($data)->except('items')->merge(['total_amount' => 0])->all());
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

            return $order->load('client', 'details.product');
        });
    }

    public function destroy(Order $order)
    {
        return DB::transaction(function () use ($order) {
            $order->load('details');
            foreach ($order->details as $detail) {
                Product::lockForUpdate()->find($detail->product_id)->increment('stock_quantity', $detail->quantity);
            }
            $order->delete();
            return response()->noContent();
        });
    }
}
