<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Ubah kolom menjadi enum dan nullable
            $table->enum('metode', ['tunai', 'qris'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Kembalikan ke string jika rollback
            $table->string('metode')->nullable()->change();
        });
    }
};
