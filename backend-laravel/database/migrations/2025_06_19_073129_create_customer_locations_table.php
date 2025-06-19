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
        Schema::create('customer_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('start_address')->nullable();
            $table->decimal('start_lat', 10, 7);
            $table->decimal('start_lng', 10, 7);
            $table->string('dest_address')->nullable();
            $table->decimal('dest_lat', 10, 7);
            $table->decimal('dest_lng', 10, 7);
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_locations', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
        });

        Schema::dropIfExists('customer_locations');
    }
};
