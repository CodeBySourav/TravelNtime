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
        Schema::table('hotel_bookings', function (Blueprint $table) {

    $table->unsignedBigInteger('change_request_id')->nullable();

    $table->decimal('refund_amount',10,2)->nullable();

    $table->decimal('cancellation_charge',10,2)->nullable();

    $table->string('change_request_status')->nullable();

    $table->timestamp('cancelled_at')->nullable();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            //
        });
    }
};
