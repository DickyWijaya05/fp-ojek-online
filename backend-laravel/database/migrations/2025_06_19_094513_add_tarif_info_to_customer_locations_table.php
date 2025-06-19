<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTarifInfoToCustomerLocationsTable extends Migration
{
    /**
     * Tambah kolom tarif dan estimasi ke tabel customer_locations
     */
    public function up(): void
    {
        Schema::table('customer_locations', function (Blueprint $table) {
            $table->decimal('distance_km', 8, 2)->nullable()->after('dest_address');
            $table->decimal('duration_min', 8, 2)->nullable()->after('distance_km');
            $table->decimal('total_price', 10, 2)->nullable()->after('duration_min');
        });
    }

    /**
     * Rollback perubahan kolom tarif dan estimasi
     */
    public function down(): void
    {
        Schema::table('customer_locations', function (Blueprint $table) {
            $table->dropColumn(['distance_km', 'duration_min', 'total_price']);
        });
    }
}
