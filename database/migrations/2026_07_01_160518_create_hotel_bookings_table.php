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
        Schema::create('hotel_bookings', function (Blueprint $table) {

        $table->id();

        $table->foreignId('user_id')->nullable();

        $table->string('booking_id')->nullable();

        $table->string('booking_ref')->nullable();

        $table->string('confirmation_no')->nullable();

        $table->string('trace_id')->nullable();

        $table->string('hotel_code')->nullable();

        $table->string('hotel_name');

        $table->string('lead_name');

        $table->string('email');

        $table->string('mobile');

        $table->decimal('amount',12,2)->default(0);

        $table->string('booking_status')->nullable();

        $table->longText('response')->nullable();

        $table->timestamps();

    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_bookings');
    }
};
