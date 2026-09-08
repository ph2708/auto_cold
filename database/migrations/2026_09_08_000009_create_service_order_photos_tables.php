<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_order_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Quem tirou/enviou a foto
            $table->enum('stage', ['before', 'after', 'diagnostic'])->default('before'); // before (Antes / Entrada), after (Depois / Concluído), diagnostic (Durante / Peça Danificada)
            $table->string('file_path'); // Caminho do arquivo armazenado
            $table->string('title')->nullable(); // Ex: Painel com luz acesa, Lataria arranhada, Chicote queimado
            $table->text('description')->nullable(); // Observações do estado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_order_photos');
    }
};
