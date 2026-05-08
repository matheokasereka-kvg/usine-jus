<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RawMaterial;

class InternalStockAlertController extends Controller
{
    public function __invoke()
    {
        $products = Product::query()
            ->whereColumn('stock_quantity', '<=', 'alert_threshold')
            ->orderBy('stock_quantity')
            ->get(['id', 'name', 'sku', 'unit', 'stock_quantity', 'alert_threshold']);

        $materials = RawMaterial::with('supplier:id,name')
            ->whereColumn('stock_quantity', '<=', 'alert_threshold')
            ->orderBy('stock_quantity')
            ->get(['id', 'supplier_id', 'name', 'unit', 'stock_quantity', 'alert_threshold']);

        return response()->json([
            'total_alerts' => $products->count() + $materials->count(),
            'products' => $products,
            'raw_materials' => $materials,
        ]);
    }
}
