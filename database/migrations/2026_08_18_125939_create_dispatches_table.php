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
        Schema::create('dispatches', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('relief_request_id')->nullable()->constrained('relief_requests')->onDelete('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');

            // Tracking & Details
            $table->string('dispatch_code')->unique()->nullable(); // e.g. DISP-2026-001
            $table->string('vehicle_number')->nullable();
            $table->string('destination')->nullable();

            // Status Column (Dashboard Count ပြုလုပ်ရန် လိုအပ်ပါသည်)
            $table->enum('status', ['Pending', 'Preparing', 'In Transit', 'Delivered', 'Cancelled'])->default('Pending');

            // Dates & Timestamps
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatches');
    }
};
