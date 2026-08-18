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
        Schema::table('donation_payments', function (Blueprint $table) {
            $table->dropColumn('total_amount'); // total_amount column ကို ဖယ်ရှားခြင်း
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donation_payments', function (Blueprint $table) {
            $table->decimal('total_amount', 10, 2)->nullable(); // Rollback ပြန်လုပ်လျှင် ပြန်ထည့်ရန်
        });
    }
};
