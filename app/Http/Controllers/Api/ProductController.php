<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() { return Product::latest()->paginate(20); }
    public function show(Product $product) { return $product; }

    public function store(Request $request)
    {
        return Product::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:80', 'unique:products,sku'],
            'unit' => ['required', 'string', 'max:40'],
            'stock_quantity' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'alert_threshold' => ['nullable', 'numeric', 'min:0'],
        ]));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:80', 'unique:products,sku,'.$product->id],
            'unit' => ['required', 'string', 'max:40'],
            'stock_quantity' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'alert_threshold' => ['required', 'numeric', 'min:0'],
        ]));

        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}
