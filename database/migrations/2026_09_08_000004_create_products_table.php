<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('default_supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            
            // Identificação e Códigos
            $table->string('sku')->unique(); // Código interno
            $table->string('barcode')->nullable()->unique(); // Código de Barras EAN
            $table->string('oem_code')->nullable(); // Código Original / OEM
            $table->string('manufacturer_code')->nullable(); // Código Fabricante
            
            // Dados da Peça
            $table->string('name');
            $table->string('brand')->nullable(); // Bosch, Moura, DNI, Magneti Marelli, etc.
            $table->text('description')->nullable();
            
            // Especificações Técnicas para Auto Elétrica
            $table->string('voltage')->nullable(); // 12V, 24V, Bivolt
            $table->string('amperage')->nullable(); // 60Ah, 70A, 90A
            $table->string('power')->nullable(); // 1.4kW, 55W
            $table->string('pin_count')->nullable(); // 4 pinos, 5 pinos
            $table->string('vehicle_compatibility')->nullable(); // Modelos compatíveis
            
            // Armazenamento Físico & Controle
            $table->string('location')->nullable(); // Prateleira / Gaveta
            $table->string('unit', 10)->default('UN'); // UN, PC, KT, MT
            
            // Estoque e Custos
            $table->decimal('current_stock', 12, 2)->default(0);
            $table->decimal('min_stock', 12, 2)->default(1);
            $table->decimal('cost_price', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
