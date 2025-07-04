<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('driver_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id');
            $table->decimal('rating', 3, 2);
            $table->text('comment')->nullable(); // opsional komentar
            $table->timestamps();

            // Relasi foreign key
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            // Mencegah rating ganda untuk satu order oleh user
            $table->unique(['user_id', 'order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_ratings');
    }
};
