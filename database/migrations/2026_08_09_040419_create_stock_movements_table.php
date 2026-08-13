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

            $table->foreignId('item_id')
                ->constrained('items')
                ->cascadeOnDelete();

            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->cascadeOnDelete();

            // Movement Types
            $table->enum('type', ['IN', 'OUT', 'TRANSFER', 'ADJUSTMENT']);

            $table->integer('quantity');
            $table->integer('balance_after')->nullable();

            // Expiry Date Column
            $table->date('expiry_date')->nullable();

            $table->string('reference')->nullable(); // Ex: Donation Ref, Request Ref
            $table->text('note')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
