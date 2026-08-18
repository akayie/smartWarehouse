<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donation_payments', function (Blueprint $table) {
            // မူရင်း amount အပြင် သုံးစွဲရန်ကျန်ရှိသော လက်ကျန်ငွေကို သိမ်းဆည်းရန် (လိုသਸ਼ုံးပါက ထည့်နိုင်ပါသည်)
            $table->decimal('remaining_amount', 15, 2)->nullable()->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('donation_payments', function (Blueprint $table) {
            $table->dropColumn('remaining_amount');
        });
    }
};
