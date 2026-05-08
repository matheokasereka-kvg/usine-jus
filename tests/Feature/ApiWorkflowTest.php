<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_login_and_stock_workflow(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.local',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $token = $this->postJson('/api/login', [
            'email' => 'admin@test.local',
            'password' => 'password',
        ])->assertOk()->json('access_token');

        $supplier = Supplier::create(['name' => 'Fournisseur']);
        $material = RawMaterial::create([
            'supplier_id' => $supplier->id,
            'name' => 'Orange',
            'unit' => 'kg',
            'stock_quantity' => 100,
            'unit_cost' => 300,
        ]);
        $product = Product::create([
            'name' => 'Jus orange',
            'sku' => 'JO-50',
            'unit' => 'bouteille',
            'stock_quantity' => 0,
            'sale_price' => 1000,
        ]);
        $client = Client::create(['name' => 'Client test']);

        $this->withToken($token)->postJson('/api/productions', [
            'product_id' => $product->id,
            'production_date' => now()->toDateString(),
            'quantity_produced' => 10,
            'materials' => [
                ['raw_material_id' => $material->id, 'quantity_used' => 25],
            ],
        ])->assertCreated();

        $this->assertDatabaseHas('raw_materials', ['id' => $material->id, 'stock_quantity' => 75]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_quantity' => 10]);

        $this->withToken($token)->postJson('/api/orders', [
            'client_id' => $client->id,
            'order_date' => now()->toDateString(),
            'items' => [
                ['product_id' => $product->id, 'quantity' => 3],
            ],
        ])->assertCreated()->assertJsonPath('total_amount', 3000);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_quantity' => 7]);
    }
}
