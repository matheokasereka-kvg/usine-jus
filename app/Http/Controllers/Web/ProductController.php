<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index', ['products' => Product::latest()->paginate(10)]);
    }

    public function show(Product $product)
    {
        return view('products.show', [
            'product' => $product->load('productions.details.rawMaterial', 'orderDetails.order.client'),
        ]);
    }

    public function store(Request $request)
    {
        Product::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:80', 'unique:products,sku'],
            'unit' => ['required', 'string', 'max:40'],
            'stock_quantity' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'alert_threshold' => ['required', 'numeric', 'min:0'],
        ]));

        return back()->with('success', 'Produit ajoute.');
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

        return back()->with('success', 'Produit modifie.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produit supprime.');
    }
}
