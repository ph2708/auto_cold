<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StockManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_user_can_login_with_username()
    {
        $response = $this->post('/login', [
            'login' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_stock_entry_increments_product_stock_and_creates_movement()
    {
        $admin = User::where('username', 'admin')->first();
        $product = Product::first();
        $initialStock = (float)$product->current_stock;

        $response = $this->actingAs($admin)->post(route('stock.entry.store'), [
            'product_id' => $product->id,
            'supplier_id' => $product->default_supplier_id,
            'reason' => 'purchase',
            'quantity' => 5,
            'unit_cost' => 150.00,
            'document_number' => 'NF-TEST-001',
            'notes' => 'Teste automatizado de entrada',
        ]);

        $response->assertRedirect(route('stock.index'));
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 5,
            'document_number' => 'NF-TEST-001',
        ]);

        $product->refresh();
        $this->assertEquals($initialStock + 5, (float)$product->current_stock);
    }

    public function test_stock_exit_decrements_product_stock_and_blocks_excess()
    {
        $admin = User::where('username', 'admin')->first();
        $product = Product::where('current_stock', '>', 5)->first();
        $initialStock = (float)$product->current_stock;

        // Saída válida vinculada a veículo
        $response = $this->actingAs($admin)->post(route('stock.exit.store'), [
            'product_id' => $product->id,
            'reason' => 'service_order',
            'quantity' => 2,
            'vehicle_plate' => 'ABC1D23',
            'service_order_number' => 'OS-100',
        ]);

        $response->assertRedirect(route('stock.index'));
        $product->refresh();
        $this->assertEquals($initialStock - 2, (float)$product->current_stock);

        // Tentativa de saída com saldo superior ao existente
        $invalidResponse = $this->actingAs($admin)->post(route('stock.exit.store'), [
            'product_id' => $product->id,
            'reason' => 'direct_sale',
            'quantity' => 999999,
        ]);

        $invalidResponse->assertSessionHasErrors('quantity');
    }
}
