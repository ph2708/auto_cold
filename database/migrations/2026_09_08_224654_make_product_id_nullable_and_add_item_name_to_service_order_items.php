<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_order_items', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->change();
            $table->string('item_name')->nullable()->after('product_id');
            $table->string('unit')->default('UN')->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('service_order_items', function (Blueprint $table) {
            $table->dropColumn(['item_name', 'unit']);
            $table->foreignId('product_id')->nullable(false)->change();
        });
    }
};
