<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\Production;
use App\Models\RawMaterial;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard.index', [
            'productsCount' => Product::count(),
            'materialsCount' => RawMaterial::count(),
            'clientsCount' => Client::count(),
            'ordersCount' => Order::count(),
            'productionsCount' => Production::count(),
            'salesTotal' => Order::sum('total_amount'),
            'lowProducts' => Product::whereColumn('stock_quantity', '<=', 'alert_threshold')->get(),
            'lowMaterials' => RawMaterial::whereColumn('stock_quantity', '<=', 'alert_threshold')->get(),
            'latestOrders' => Order::with('client')->latest()->take(5)->get(),
        ]);
    }
}
