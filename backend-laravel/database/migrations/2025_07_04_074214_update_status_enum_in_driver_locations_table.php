<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE driver_locations MODIFY status ENUM('available', 'offline') NOT NULL DEFAULT 'offline'");
    }

    public function down(): void
    {
        // Balikin ke enum sebelumnya kalau dibutuhkan
        DB::statement("ALTER TABLE driver_locations MODIFY status ENUM('available', 'on_going') NOT NULL DEFAULT 'offline'");
    }
};
