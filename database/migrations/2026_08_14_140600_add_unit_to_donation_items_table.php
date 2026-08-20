<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donation_items', function (Blueprint $table) {
            // Check if the column doesn't exist yet before adding it
            if (!Schema::hasColumn('donation_items', 'unit')) {
                $table->string('unit')->nullable()->after('quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donation_items', function (Blueprint $table) {
            if (Schema::hasColumn('donation_items', 'unit')) {
                $table->dropColumn('unit');
            }
        });
    }
};
