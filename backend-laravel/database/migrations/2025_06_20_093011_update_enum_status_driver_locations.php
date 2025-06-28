<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Ubah semua 'offline' ke 'available' dulu agar tidak error saat ubah enum
        DB::table('driver_locations')
            ->where('status', 'offline')
            ->update(['status' => 'available']);

        // Ubah struktur enum hanya jadi 'available' dan 'on_going'
        DB::statement("ALTER TABLE driver_locations MODIFY status ENUM('available', 'on_going') NOT NULL DEFAULT 'available'");
    }

    public function down(): void
    {
        // Rollback ke enum sebelumnya
        DB::statement("ALTER TABLE driver_locations MODIFY status ENUM('available', 'on_going', 'offline') NOT NULL DEFAULT 'available'");
    }
};
