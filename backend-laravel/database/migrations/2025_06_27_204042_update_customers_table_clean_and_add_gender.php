<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCustomersTableCleanAndAddGender extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Hapus kolom yang redundant
            $table->dropColumn(['id_customer', 'nama', 'nomor_telepon']);

            // Tambahkan kolom user_id dan relasi ke users
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');

            // Tambahkan jenis kelamin
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('alamat');

            // Ubah rating jadi 3,2 langsung sekalian (kalau belum)
            $table->decimal('rating', 3, 2)->default(0.0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'jenis_kelamin']);

            $table->string('id_customer', 255);
            $table->string('nama', 255);
            $table->string('nomor_telepon', 20)->nullable();

            $table->decimal('rating', 2, 1)->default(0.0)->change();
        });
    }
}
