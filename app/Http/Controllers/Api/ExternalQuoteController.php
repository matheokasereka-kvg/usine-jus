<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExternalQuoteController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.sku' => ['required', 'string', Rule::exists('products', 'sku')],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
        ]);

        $products = Product::whereIn('sku', collect($data['items'])->pluck('sku'))->get()->keyBy('sku');
        $lines = collect($data['items'])->map(function (array $item) use ($products) {
            $product = $products->get($item['sku']);
            $quantity = (float) $item['quantity'];
            $unitPrice = (float) $product->sale_price;

            return [
                'sku' => $product->sku,
                'name' => $product->name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $quantity * $unitPrice,
                'available' => (float) $product->stock_quantity >= $quantity,
                'available_quantity' => (float) $product->stock_quantity,
            ];
        })->values();

        return response()->json([
            'currency' => 'XAF',
            'lines' => $lines,
            'subtotal' => $lines->sum('line_total'),
            'can_fulfill' => $lines->every(fn (array $line) => $line['available']),
        ]);
    }
}
