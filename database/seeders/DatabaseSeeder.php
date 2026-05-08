<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@usine-jus.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Employe Demo',
            'email' => 'employe@usine-jus.test',
            'password' => 'password',
            'role' => 'employe',
        ]);

        $supplier = Supplier::create([
            'name' => 'Fruits du Verger',
            'phone' => '+237 600 000 001',
            'email' => 'contact@verger.test',
            'address' => 'Marche central',
        ]);

        RawMaterial::create([
            'supplier_id' => $supplier->id,
            'name' => 'Mangues',
            'unit' => 'kg',
            'stock_quantity' => 500,
            'unit_cost' => 350,
            'alert_threshold' => 50,
        ]);

        RawMaterial::create([
            'supplier_id' => $supplier->id,
            'name' => 'Sucre',
            'unit' => 'kg',
            'stock_quantity' => 120,
            'unit_cost' => 800,
            'alert_threshold' => 20,
        ]);

        Product::create([
            'name' => 'Jus de mangue 50cl',
            'sku' => 'JUS-MANGUE-50',
            'unit' => 'bouteille',
            'stock_quantity' => 40,
            'sale_price' => 1000,
            'alert_threshold' => 20,
        ]);

        Client::create([
            'name' => 'Boutique Soleil',
            'phone' => '+237 600 000 002',
            'email' => 'achat@soleil.test',
            'address' => 'Centre-ville',
        ]);
    }
}
