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
        Schema::create('donation_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('donation_id')
                ->constrained('donations')
                ->restrictOnDelete();

            $table->foreignId('item_id')
                ->constrained('items')
                ->restrictOnDelete();

            $table->unsignedInteger('quantity');
            $table->string('unit')->nullable(); // Dropdown/Text Unit (ဥပမာ - ထုပ်၊ ဗူး၊ ဖာ)
            $table->date('expired_date')->nullable(); // Expired Date (Food, Water, Medical အတွက်)

            $table->timestamps();
            $table->softDeletes();

            /*
             * Prevent duplicate active item
             * records for the same donation.
             *
             * NOTE:
             * Soft deleted records are not considered
             * by this database constraint.
             */
            $table->unique(['donation_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_items');
    }
};
