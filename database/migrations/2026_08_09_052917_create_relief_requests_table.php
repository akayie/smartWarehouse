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
        Schema::create('relief_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('disaster_id')
                ->constrained('disasters')
                ->restrictOnDelete();

            $table->foreignId('requested_by')
                ->constrained('users')
                ->restrictOnDelete();

            // Added contact fields
            $table->string('name');
            $table->string('phone_number');

            $table->string('location');

            $table->date('request_date');

            $table->string('status')
                ->default('Pending');

            $table->text('note')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relief_requests');
    }
};
