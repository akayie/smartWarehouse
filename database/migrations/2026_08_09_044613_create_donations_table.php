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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            // Donor
            $table->foreignId('donor_id')
                ->constrained('donors')
                ->restrictOnDelete();

            // Warehouse - Optional
            $table->foreignId('warehouse_id')
                ->nullable()
                ->constrained('warehouses')
                ->nullOnDelete();

            // Donation Type
            $table->string('donation_type');

            // Donation Date
            $table->date('donation_date');

            // Donation Status
            $table->enum('status', [
                'Pending',
                'Received',
                'Cancelled'
            ])->default('Pending');

            // Note
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
