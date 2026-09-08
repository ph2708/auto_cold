<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Product;
use App\Models\ServiceOrder;
use App\Models\PurchaseOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceOrderAndPurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_create_service_order_apply_part_and_decrement_stock()
    {
        $admin = User::where('username', 'admin')->first();
        
        // 1. Criar Cliente e Veículo
        $customer = Customer::create([
            'name' => 'João Motorista',
            'whatsapp' => '(11) 98888-1111',
        ]);

        $vehicle = Vehicle::create([
            'customer_id' => $customer->id,
            'plate' => 'ABC1D23',
            'brand' => 'Volkswagen',
            'model' => 'Gol 1.6',
        ]);

        $product = Product::where('current_stock', '>', 5)->first();
        $initialStock = (float)$product->current_stock;

        // 2. Abrir OS
        $response = $this->actingAs($admin)->post(route('service_orders.store'), [
            'order_number' => 'OS-TEST-0099',
            'customer_name' => $customer->name,
            'customer_phone' => $customer->whatsapp,
            'vehicle_plate' => $vehicle->plate,
            'vehicle_model' => $vehicle->model,
            'vehicle_brand' => $vehicle->brand,
            'user_id' => $admin->id,
            'status' => 'in_progress',
            'reported_defect' => 'Alternador não gera carga suficiente para a bateria.',
            'entry_date' => date('Y-m-d'),
        ]);

        $os = ServiceOrder::where('order_number', 'OS-TEST-0099')->first();
        $this->assertNotNull($os);

        // 3. Adicionar Peça Elétrica na OS (Gera baixa automática no estoque)
        $this->actingAs($admin)->post(route('service_orders.items.store', $os), [
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->selling_price,
        ]);

        $product->refresh();
        $os->refresh();

        $this->assertEquals($initialStock - 1, (float)$product->current_stock);
        $this->assertEquals($product->selling_price, (float)$os->products_total);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'out',
            'reason' => 'service_order',
            'service_order_number' => 'OS-TEST-0099',
        ]);
    }

    public function test_can_receive_purchase_order_and_increment_stock()
    {
        $admin = User::where('username', 'admin')->first();
        $product = Product::first();
        $initialStock = (float)$product->current_stock;

        $po = PurchaseOrder::create([
            'order_code' => 'PED-TEST-0099',
            'origin' => 'Mercado Livre',
            'item_name' => 'Peça Teste ML',
            'product_id' => $product->id,
            'quantity' => 4,
            'unit_cost' => 120.00,
            'total_cost' => 480.00,
            'status' => 'shipped',
            'purchase_date' => date('Y-m-d'),
        ]);

        // Receber a encomenda na oficina
        $response = $this->actingAs($admin)->post(route('purchase_orders.receive', $po));
        $response->assertRedirect();

        $po->refresh();
        $product->refresh();

        $this->assertEquals('delivered', $po->status);
        $this->assertEquals($initialStock + 4, (float)$product->current_stock);
    }
}
