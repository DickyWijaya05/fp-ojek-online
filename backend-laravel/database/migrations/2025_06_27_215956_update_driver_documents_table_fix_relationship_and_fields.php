<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('driver_documents', function (Blueprint $table) {
            // Ganti driver_id → user_id
            $table->renameColumn('driver_id', 'user_id');

            // Drop vehicle_type
            $table->dropColumn('vehicle_type');

            // Tambah kolom warna kendaraan dan plat nomor
            $table->string('vehicle_color')->after('vehicle_name');
            $table->string('plate_number')->after('vehicle_color');
        });
    }

    public function down()
    {
        Schema::table('driver_documents', function (Blueprint $table) {
            // Rollback: ubah user_id → driver_id
            $table->renameColumn('user_id', 'driver_id');

            // Tambah lagi vehicle_type
            $table->string('vehicle_type')->after('vehicle_name');

            // Drop kolom yang baru ditambah
            $table->dropColumn(['vehicle_color', 'plate_number']);
        });
    }
};

