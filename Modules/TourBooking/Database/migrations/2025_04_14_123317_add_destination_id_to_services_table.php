<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'destination_id')) {
                $table->foreignId('destination_id')
                    ->nullable()
                    ->after('service_type_id')
                    ->constrained('destinations')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('services', 'destination_id')) {

            // Check whether the foreign key actually exists
            $foreignKey = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'services'
                  AND COLUMN_NAME = 'destination_id'
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            Schema::table('services', function (Blueprint $table) use ($foreignKey) {
                if (!empty($foreignKey)) {
                    $table->dropForeign(['destination_id']);
                }

                $table->dropColumn('destination_id');
            });
        }
    }
};