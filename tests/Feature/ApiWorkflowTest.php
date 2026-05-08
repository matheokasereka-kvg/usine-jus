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

    public function test_internal_dashboard_and_stock_alert_apis(): void
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

        Product::create([
            'name' => 'Jus orange',
            'sku' => 'JO-50',
            'unit' => 'bouteille',
            'stock_quantity' => 2,
            'sale_price' => 1000,
            'alert_threshold' => 5,
        ]);
        RawMaterial::create([
            'name' => 'Orange',
            'unit' => 'kg',
            'stock_quantity' => 4,
            'unit_cost' => 300,
            'alert_threshold' => 10,
        ]);

        $this->withToken($token)->getJson('/api/internal/dashboard-summary')
            ->assertOk()
            ->assertJsonPath('counts.products', 1)
            ->assertJsonPath('finance.product_stock_value', 2000);

        $this->withToken($token)->getJson('/api/internal/stock-alerts')
            ->assertOk()
            ->assertJsonPath('total_alerts', 2)
            ->assertJsonPath('products.0.sku', 'JO-50')
            ->assertJsonPath('raw_materials.0.name', 'Orange');
    }

    public function test_external_catalog_and_quote_apis(): void
    {
        Product::create([
            'name' => 'Jus mangue',
            'sku' => 'JM-50',
            'unit' => 'bouteille',
            'stock_quantity' => 12,
            'sale_price' => 1200,
            'alert_threshold' => 3,
        ]);

        $this->getJson('/api/external/catalog')
            ->assertOk()
            ->assertJsonPath('data.0.sku', 'JM-50')
            ->assertJsonPath('data.0.currency', 'XAF');

        $this->postJson('/api/external/quotes', [
            'items' => [
                ['sku' => 'JM-50', 'quantity' => 3],
            ],
        ])->assertOk()
            ->assertJsonPath('subtotal', 3600)
            ->assertJsonPath('can_fulfill', true)
            ->assertJsonPath('lines.0.available_quantity', 12);
    }

}
