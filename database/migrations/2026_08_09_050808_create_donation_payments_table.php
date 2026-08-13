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
        Schema::create('donation_payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('donation_id')->constrained()->onDelete('cascade');
    $table->string('payment_method'); // e.g. KBZPay, WaveMoney, Cash, Bank Transfer
    $table->string('transaction_reference')->nullable();
    $table->date('payment_date');
    $table->string('account_name')->nullable();
    $table->string('account_number')->nullable();
    $table->decimal('amount', 15, 2);
    $table->string('currency', 10)->default('MMK');
    $table->string('proof')->nullable(); // Slip image
    $table->enum('status', ['Pending', 'Completed', 'Rejected'])->default('Completed');
    $table->text('note')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_payments');
    }
};
