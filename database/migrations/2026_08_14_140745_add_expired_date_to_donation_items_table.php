<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donation_items', function (Blueprint $table) {
            // unit ပြီးလျှင် expired_date column ကို ထည့်သွင်းမည် (အကယ်၍ မရှိသေးမှသာ)
            if (!Schema::hasColumn('donation_items', 'expired_date')) {
                $table->date('expired_date')->nullable()->after('unit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donation_items', function (Blueprint $table) {
            if (Schema::hasColumn('donation_items', 'expired_date')) {
                $table->dropColumn('expired_date');
            }
        });
    }
};
