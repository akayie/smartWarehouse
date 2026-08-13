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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->cascadeOnDelete();

            $table->foreignId('item_id')
                ->constrained('items')
                ->cascadeOnDelete();

            $table->integer('quantity')
                ->default(0);

            // 1. Expiry Date Column ထည့်သွင်းခြင်း (->after('quantity') ကို ဖြုတ်ထားပါသည်)
            $table->date('expiry_date')->nullable();

            $table->timestamps();

            // Soft delete
            $table->softDeletes();

            // 2. Warehouse + Item + Expiry Date သုံးခုပေါင်းမှ Unique ဖြစ်စေရန် ပြင်ဆင်ခြင်း
            $table->unique([
                'warehouse_id',
                'item_id',
                'expiry_date'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
