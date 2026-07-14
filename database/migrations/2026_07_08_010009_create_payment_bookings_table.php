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
        Schema::create('payment_bookings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('booking_id');

            $table->string('payment_for'); // flight, hotel, bus, holiday

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('gateway')->default('Razorpay');

            $table->string('payment_id')->nullable();
            $table->string('order_id')->nullable();
            $table->string('signature')->nullable();

            $table->decimal('amount', 10, 2);

            $table->string('currency')->default('INR');

            $table->string('payment_method')->nullable();

            $table->string('status')->default('Pending');

            $table->json('response')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_bookings');
    }
};
