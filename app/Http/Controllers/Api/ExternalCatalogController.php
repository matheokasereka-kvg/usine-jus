<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ExternalCatalogController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'data' => Product::query()
                ->where('stock_quantity', '>', 0)
                ->orderBy('name')
                ->get(['id', 'name', 'sku', 'unit', 'stock_quantity', 'sale_price'])
                ->map(fn (Product $product) => [
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'unit' => $product->unit,
                    'available_quantity' => (float) $product->stock_quantity,
                    'unit_price' => (float) $product->sale_price,
                    'currency' => 'XAF',
                ]),
        ]);
    }
}
