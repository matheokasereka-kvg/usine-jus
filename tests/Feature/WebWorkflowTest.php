<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_use_browser_pages_for_stock_workflow(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.local',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $supplier = Supplier::create(['name' => 'Fournisseur']);
        $material = RawMaterial::create([
            'supplier_id' => $supplier->id,
            'name' => 'Mangue',
            'unit' => 'kg',
            'stock_quantity' => 50,
            'unit_cost' => 300,
        ]);
        $product = Product::create([
            'name' => 'Jus mangue',
            'sku' => 'JM-50',
            'unit' => 'bouteille',
            'stock_quantity' => 0,
            'sale_price' => 1000,
        ]);
        $client = Client::create(['name' => 'Client test']);

        $this->actingAs($user)->get('/dashboard')->assertOk()->assertSee('Dashboard');

        $this->actingAs($user)->post('/productions', [
            'product_id' => $product->id,
            'production_date' => now()->toDateString(),
            'quantity_produced' => 8,
            'materials' => [
                ['raw_material_id' => $material->id, 'quantity_used' => 20],
                ['raw_material_id' => '', 'quantity_used' => ''],
            ],
        ])->assertRedirect();

        $this->assertDatabaseHas('raw_materials', ['id' => $material->id, 'stock_quantity' => 30]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_quantity' => 8]);

        $this->actingAs($user)->post('/orders', [
            'client_id' => $client->id,
            'order_date' => now()->toDateString(),
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
                ['product_id' => '', 'quantity' => ''],
            ],
        ])->assertRedirect();

        $this->assertDatabaseHas('orders', ['client_id' => $client->id, 'total_amount' => 2000]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_quantity' => 6]);
    }

    public function test_home_route_sends_guests_to_login_and_users_to_dashboard(): void
    {
        $this->get('/')->assertRedirect('/login');

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.local',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->actingAs($user)->get('/')->assertRedirect('/dashboard');
    }

    public function test_authenticated_user_can_open_main_navigation_pages(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.local',
            'password' => 'password',
            'role' => 'admin',
        ]);

        Supplier::create(['name' => 'Fournisseur']);
        Client::create(['name' => 'Client test']);
        Product::create([
            'name' => 'Jus mangue',
            'sku' => 'JM-50',
            'unit' => 'bouteille',
            'stock_quantity' => 10,
            'sale_price' => 1000,
        ]);
        RawMaterial::create([
            'name' => 'Mangue',
            'unit' => 'kg',
            'stock_quantity' => 50,
            'unit_cost' => 300,
        ]);

        foreach (['/dashboard', '/products', '/raw-materials', '/productions', '/orders', '/clients'] as $path) {
            $this->actingAs($user)->get($path)->assertOk();
        }
    }

    public function test_authenticated_user_can_open_detail_pages_from_navigation_links(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.local',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $client = Client::create(['name' => 'Client test']);
        $material = RawMaterial::create([
            'name' => 'Mangue',
            'unit' => 'kg',
            'stock_quantity' => 50,
            'unit_cost' => 300,
        ]);
        $product = Product::create([
            'name' => 'Jus mangue',
            'sku' => 'JM-50',
            'unit' => 'bouteille',
            'stock_quantity' => 20,
            'sale_price' => 1000,
        ]);

        $this->actingAs($user)->post('/productions', [
            'product_id' => $product->id,
            'production_date' => now()->toDateString(),
            'quantity_produced' => 5,
            'materials' => [
                ['raw_material_id' => $material->id, 'quantity_used' => 10],
            ],
        ]);

        $this->actingAs($user)->post('/orders', [
            'client_id' => $client->id,
            'order_date' => now()->toDateString(),
            'items' => [
                ['product_id' => $product->id, 'quantity' => 3],
            ],
        ]);

        $production = \App\Models\Production::first();
        $order = \App\Models\Order::first();

        foreach ([
            "/clients/{$client->id}",
            "/products/{$product->id}",
            "/raw-materials/{$material->id}",
            "/productions/{$production->id}",
            "/orders/{$order->id}",
        ] as $path) {
            $this->actingAs($user)->get($path)->assertOk();
        }
    }
}
