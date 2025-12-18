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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');

            // Relasi ke tabel users
            $table->unsignedBigInteger('user_id');

            // Relasi ke tabel cities
            $table->unsignedBigInteger('city_id')->nullable();

            // Informasi data customer
            $table->string('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('avatar')->nullable();

            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('city_id')
                  ->references('id')
                  ->on('cities')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
