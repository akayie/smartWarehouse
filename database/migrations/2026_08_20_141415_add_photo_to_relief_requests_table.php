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

            // ကျန်းမာရေးဆိုင်ရာ တောင်းခံမှု ဟုတ်/မဟုတ်
            $table->boolean('is_health_related')
                ->default(false)
                ->after('phone_number');

            // ဆရာဝန်ထောက်ခံချက် / ဆေးမှတ်တမ်းပုံ
            $table->string('medical_proof')
                ->nullable()
                ->after('is_health_related');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('relief_requests', function (Blueprint $table) {

            $table->dropColumn([
                'is_health_related',
                'medical_proof',
            ]);

        });
    }
};
