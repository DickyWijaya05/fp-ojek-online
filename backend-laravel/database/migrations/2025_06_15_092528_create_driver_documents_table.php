<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('driver_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');

            // Dokumen
            $table->string('pas_photo');         // Pas Foto
            $table->string('ktp');               // Foto KTP
            $table->string('sim');               // Foto SIM
            $table->string('stnk');              // Foto STNK
            $table->string('vehicle_photo');     // Foto Kendaraan
            $table->string('selfie_ktp');        // Foto Selfie dengan KTP

            // Kendaraan
            $table->string('vehicle_type');      // Jenis Kendaraan (motor / mobil)
            $table->string('vehicle_name');      // Nama / Merk Kendaraan

            $table->timestamps();

            // Foreign key ke tabel users
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('driver_documents');
    }
};
