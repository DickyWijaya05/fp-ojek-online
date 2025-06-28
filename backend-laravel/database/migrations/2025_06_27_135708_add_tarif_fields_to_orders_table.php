<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->float('distance_km')->nullable()->after('status');
            $table->float('duration_min')->nullable()->after('distance_km');
            $table->integer('total_price')->nullable()->after('duration_min');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['distance_km', 'duration_min', 'total_price']);
        });
    }
};
