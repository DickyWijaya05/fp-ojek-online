<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // Primary key auto increment
            $table->string('id_customer')->unique(); // ID unik customer, contoh: CST001
            $table->string('nama'); // Nama customer
            $table->string('foto_profil')->nullable(); // Foto profil customer
            $table->text('alamat')->nullable(); // Alamat lengkap
            $table->string('nomor_telepon', 20)->nullable(); // Nomor HP
            $table->decimal('rating', 2, 1)->default(0.0); // Rating customer
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
}
