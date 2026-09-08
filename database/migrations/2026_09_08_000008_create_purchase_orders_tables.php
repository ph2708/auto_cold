<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique(); // ex: PED-2026-0001
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->foreignId('service_order_id')->nullable()->constrained('service_orders')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Quem comprou
            
            // Origem da Compra / Marketplace
            $table->string('origin'); // Mercado Livre, Shopee, Loja Online, Fornecedor Local, Auto Peças Distribuidora, Outro
            $table->string('external_order_number')->nullable(); // Nº do Pedido no ML / NF
            $table->string('tracking_code')->nullable(); // Código de Rastreamento (ex: BR123456789)
            $table->string('tracking_url')->nullable(); // Link de rastreio direto
            
            // Descrição e Dados da Peça
            $table->string('item_name'); // Nome / Descrição da peça comprada
            $table->decimal('quantity', 12, 2)->default(1);
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            
            // Status do Envio & Chegada
            $table->enum('status', [
                'pending', // Comprado / Aguardando Envio
                'shipped', // Em Trânsito / Postado
                'delivered', // Recebido na Oficina
                'cancelled' // Cancelado / Devolvido
            ])->default('pending');

            $table->date('purchase_date')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
