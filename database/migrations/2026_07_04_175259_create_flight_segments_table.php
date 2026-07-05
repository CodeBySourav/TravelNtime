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
        Schema::create('flight_segments', function (Blueprint $table) {

    $table->id();

    $table->foreignId('booking_id')
          ->constrained('flight_bookings')
          ->cascadeOnDelete();

    $table->string('airline');

    $table->string('flight_number');

    $table->string('origin');

    $table->string('destination');

    $table->dateTime('departure');

    $table->dateTime('arrival');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_segments');
    }
};
