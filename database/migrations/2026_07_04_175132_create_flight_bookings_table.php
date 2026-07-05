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
        Schema::create('flight_bookings', function (Blueprint $table) {

    $table->id();

    $table->foreignId('user_id')->nullable();

    $table->string('booking_id')->nullable();
    $table->string('booking_ref')->nullable();
    $table->string('pnr')->nullable();

    $table->string('trace_id');
    $table->longText('result_index');

    $table->string('airline')->nullable();
    $table->string('flight_number')->nullable();

    $table->string('origin')->nullable();
    $table->string('destination')->nullable();

    $table->dateTime('departure')->nullable();
    $table->dateTime('arrival')->nullable();

    $table->decimal('published_fare',10,2)->default(0);
    $table->decimal('offered_fare',10,2)->default(0);

    $table->boolean('is_lcc')->default(false);

    $table->string('status')->default('Pending');

    $table->json('api_response')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_bookings');
    }
};
