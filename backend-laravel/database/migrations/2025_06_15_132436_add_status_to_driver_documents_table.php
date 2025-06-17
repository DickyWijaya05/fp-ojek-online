<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void {
    Schema::table('driver_documents', function (Blueprint $table) {
        $table->string('status')->default('pending')->after('vehicle_name'); // approved, pending, rejected
    });
}

public function down(): void {
    Schema::table('driver_documents', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

    };
