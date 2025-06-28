<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('drivers', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn(['id_driver', 'nama', 'nomor_telepon']);

            // Tambah kolom baru
            $table->unsignedBigInteger('user_id')->after('id');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('alamat');
            $table->decimal('rating', 3, 2)->default(0.00)->change();

            // Foreign key relasi ke users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'jenis_kelamin']);
            $table->decimal('rating', 2, 1)->default(0.0)->change();
            $table->string('id_driver')->after('id');
            $table->string('nama')->after('id_driver');
            $table->string('nomor_telepon', 20)->nullable()->after('alamat');
        });
    }
};

