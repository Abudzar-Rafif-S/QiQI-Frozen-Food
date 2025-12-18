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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel users
            $table->unsignedBigInteger('user_id')->unique();

            // Data detail admin
            $table->string('fullname');
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('avatar')->nullable();

            $table->timestamps();

            // Relasi
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
