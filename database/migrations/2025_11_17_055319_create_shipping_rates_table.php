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
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();

            // Kota tujuan (mengacu ke tabel cities)
            $table->unsignedBigInteger('city_id');

            // Harga ongkir per kg
            $table->decimal('price_per_kg', 10, 2);

            // Catatan opsional (mis: estimasi pengiriman, catatan khusus)
            $table->string('note')->nullable();

            $table->timestamps();

            // Relasi
            $table->foreign('city_id')
                  ->references('id')
                  ->on('cities')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
