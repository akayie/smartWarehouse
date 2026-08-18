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
        Schema::table('relief_requests', function (Blueprint $table) {
            // Warehouse ID (Foreign Key) ထည့်သွင်းခြင်း
            $table->foreignId('warehouse_id')
                ->nullable()
                ->after('disaster_id')
                ->constrained('warehouses')
                ->nullOnDelete();

            // Google Maps Lat/Long ရေးဆွဲရန် decimal columns များထည့်ခြင်း
            $table->decimal('latitude', 10, 8)->nullable()->after('location');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('relief_requests', function (Blueprint $table) {
            // Rollback လုပ်ပါက ကော်လံများကို ပြန်ဖြုတ်ရန်
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['warehouse_id', 'latitude', 'longitude']);
        });
    }
};
