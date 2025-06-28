<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id(); // Kolom auto increment
            $table->string('id_driver')->unique(); // ID unik untuk driver, seperti "DRV001"
            $table->string('nama'); // Nama lengkap driver
            $table->string('foto_profil')->nullable(); // Path atau URL ke foto profil
            $table->text('alamat')->nullable(); // Alamat lengkap
            $table->string('nomor_telepon', 20)->nullable(); // Nomor telepon
            $table->decimal('rating', 2, 1)->default(0.0); // Rating misalnya 4.5
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
}
