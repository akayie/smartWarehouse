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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');                  // လုပ်ဆောင်ချက် ခေါင်းစဉ် (e.g. "ပစ္စည်းအသစ် ထည့်သွင်းခြင်း")
            $table->text('description')->nullable();   // အသေးစိတ် ဖော်ပြချက်
            $table->string('location')->nullable();    // တည်နေရာ / ကုန်လှောင်ရုံ အမည်
            $table->string('status')->default('Completed'); // Pending, In Transit, Verified, Completed
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
