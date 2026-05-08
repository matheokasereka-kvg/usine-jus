<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\Production;
use App\Models\RawMaterial;
use Illuminate\Support\Facades\DB;

class InternalDashboardController extends Controller
{
    public function __invoke()
    {
        $productStockValue = Product::query()
            ->selectRaw('COALESCE(SUM(stock_quantity * sale_price), 0) as value')
            ->value('value');

        $materialStockCost = RawMaterial::query()
            ->selectRaw('COALESCE(SUM(stock_quantity * unit_cost), 0) as value')
            ->value('value');

        return response()->json([
            'counts' => [
                'products' => Product::count(),
                'raw_materials' => RawMaterial::count(),
                'clients' => Client::count(),
                'orders' => Order::count(),
                'productions' => Production::count(),
            ],
            'finance' => [
                'sales_total' => (float) Order::sum('total_amount'),
                'product_stock_value' => (float) $productStockValue,
                'material_stock_cost' => (float) $materialStockCost,
            ],
            'orders_by_status' => Order::query()
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status'),
            'latest_orders' => Order::with('client:id,name')
                ->latest()
                ->take(5)
                ->get(['id', 'client_id', 'order_date', 'status', 'total_amount']),
        ]);
    }
}
