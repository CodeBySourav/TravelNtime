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
        Schema::create('flight_passengers', function (Blueprint $table) {

    $table->id();

    $table->foreignId('booking_id')
          ->constrained('flight_bookings')
          ->cascadeOnDelete();

    $table->string('title');
    $table->string('first_name');
    $table->string('last_name');

    $table->integer('pax_type');

    $table->date('dob')->nullable();

    $table->string('gender')->nullable();

    $table->string('passport_no')->nullable();
    $table->date('passport_expiry')->nullable();

    $table->string('meal')->nullable();
    $table->string('seat')->nullable();
    $table->string('baggage')->nullable();

    $table->boolean('is_lead')->default(false);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_passengers');
    }
};
