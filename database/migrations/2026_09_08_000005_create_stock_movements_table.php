<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            
            $table->enum('type', ['in', 'out']); // in = Entrada, out = Saída
            $table->enum('reason', [
                'purchase', // Compra Fornecedor
                'adjustment_in', // Ajuste positivo inventário
                'return_in', // Devolução cliente / OS
                'service_order', // Aplicação em Ordem de Serviço
                'direct_sale', // Venda balcão
                'internal_use', // Uso interno oficina
                'loss_damage', // Perda / Avaria
                'adjustment_out' // Ajuste negativo inventário
            ]);
            
            $table->decimal('quantity', 12, 2);
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            
            // Rastreio
            $table->string('document_number')->nullable(); // NF ou Pedido
            $table->string('vehicle_plate')->nullable(); // Placa do veículo
            $table->string('service_order_number')->nullable(); // Número da OS
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
