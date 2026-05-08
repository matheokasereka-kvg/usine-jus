<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\Production;
use App\Models\RawMaterial;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $productsCount = Product::count();
        $materialsCount = RawMaterial::count();
        $clientsCount = Client::count();
        $ordersCount = Order::count();
        $productionsCount = Production::count();
        $salesTotal = Order::sum('total_amount');
        $productStockValue = Product::query()
            ->selectRaw('COALESCE(SUM(stock_quantity * sale_price), 0) as value')
            ->value('value');
        $materialStockCost = RawMaterial::query()
            ->selectRaw('COALESCE(SUM(stock_quantity * unit_cost), 0) as value')
            ->value('value');
        $ordersByStatus = Order::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('dashboard.index', [
            'productsCount' => $productsCount,
            'materialsCount' => $materialsCount,
            'clientsCount' => $clientsCount,
            'ordersCount' => $ordersCount,
            'productionsCount' => $productionsCount,
            'salesTotal' => $salesTotal,
            'productStockValue' => $productStockValue,
            'materialStockCost' => $materialStockCost,
            'ordersByStatus' => $ordersByStatus,
            'lowProducts' => Product::whereColumn('stock_quantity', '<=', 'alert_threshold')->get(),
            'lowMaterials' => RawMaterial::whereColumn('stock_quantity', '<=', 'alert_threshold')->get(),
            'latestOrders' => Order::with('client', 'details.product')->latest()->take(5)->get(),
        ]);
    }
}
