<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('document_number')->nullable(); // CPF / CNPJ
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('plate', 10)->unique(); // Placa Mercosul ou Antiga
            $table->string('brand'); // Marca (ex: VW, Fiat, Chevrolet, Ford, Toyota)
            $table->string('model'); // Modelo (ex: Gol G5 1.6, Onix 1.0, Civic)
            $table->string('year')->nullable(); // Ano Fabricação / Modelo
            $table->string('color')->nullable();
            $table->string('fuel_type')->nullable(); // Flex, Gasolina, Diesel, Elétrico/Híbrido
            $table->string('chassis')->nullable(); // Chassi
            $table->integer('current_km')->nullable(); // Quilometragem atual
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('customers');
    }
};
