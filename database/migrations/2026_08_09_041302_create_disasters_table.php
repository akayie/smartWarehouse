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
        Schema::create('disasters', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->string('type');

            $table->string('location');

            $table->date('start_date');

            $table->date('end_date')->nullable();

            $table->enum('status', [
                'Active',
                'Completed',
                'Cancelled'
            ])->default('Active');

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disasters');
    }
};
