<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_order_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 50); // status_changed, item_added, item_removed, service_added, note_added, etc.
            $table->string('from_value', 100)->nullable(); // valor anterior (ex: status antigo)
            $table->string('to_value', 100)->nullable();   // valor novo
            $table->text('description')->nullable();        // descrição legível do que mudou
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_order_logs');
    }
};
