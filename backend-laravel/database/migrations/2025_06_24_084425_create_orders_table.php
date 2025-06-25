<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id') // customer
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('driver_id') // driver
                ->constrained('users')
                ->onDelete('cascade');

            $table->decimal('start_lat', 10, 6);
            $table->decimal('start_lng', 10, 6);
            $table->decimal('dest_lat', 10, 6);
            $table->decimal('dest_lng', 10, 6);

            $table->string('start_address')->nullable();
            $table->string('dest_address')->nullable();

            $table->string('status')->default('pending'); // pending, accepted, ontrip, finished, canceled

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

