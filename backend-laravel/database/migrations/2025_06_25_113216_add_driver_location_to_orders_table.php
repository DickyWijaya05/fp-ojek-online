<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->decimal('driver_lat', 10, 6)->nullable()->after('driver_id');
        $table->decimal('driver_lng', 10, 6)->nullable()->after('driver_lat');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['driver_lat', 'driver_lng']);
    });
}
};
