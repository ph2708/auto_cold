<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // ex: OS-2026-0001
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Técnico / Eletricista responsável
            
            $table->enum('status', [
                'budget', // Orçamento
                'approved', // Aprovada pelo Cliente
                'in_progress', // Em Execução
                'waiting_parts', // Aguardando Peças
                'completed', // Concluída / Testada
                'delivered', // Entregue / Finalizada
                'cancelled' // Cancelada
            ])->default('budget');

            // Descrições e Diagnósticos Técnicos
            $table->text('reported_defect'); // Defeito relatado pelo cliente (ex: não dá partida, bateria descarregando)
            $table->text('technical_diagnosis')->nullable(); // Diagnóstico técnico / Laudo do eletricista
            $table->text('solution_applied')->nullable(); // Solução aplicada / Testes realizados

            // Valores Financeiros
            $table->decimal('services_total', 12, 2)->default(0); // Total Mão de Obra
            $table->decimal('products_total', 12, 2)->default(0); // Total Peças
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0); // Total Geral da OS

            // Prazos e KM de entrada
            $table->integer('entry_km')->nullable();
            $table->date('entry_date')->nullable();
            $table->date('expected_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->text('internal_notes')->nullable();

            $table->timestamps();
        });

        // Peças aplicadas na OS (Integradas ao estoque ou cotadas externamente)
        Schema::create('service_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('item_name')->nullable();
            $table->decimal('quantity', 12, 2);
            $table->string('unit')->default('UN');
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->timestamps();
        });

        // Serviços de mão de obra executados
        Schema::create('service_order_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->cascadeOnDelete();
            $table->string('description'); // ex: Revisão alternador na bancada, substituição de chicote
            $table->decimal('quantity', 12, 2)->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_order_services');
        Schema::dropIfExists('service_order_items');
        Schema::dropIfExists('service_orders');
    }
};
