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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Kolom inti
            $table->string('product_name')->index();
            $table->string('picture');
            $table->text('description');

            // Foreign Keys
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('discount_id')->nullable();

            // Informasi lainnya
            $table->unsignedInteger('stock')->default(0)->index();
            $table->decimal('price', 10, 2)->index();
            $table->decimal('weight', 10, 2);

            $table->timestamps();

            // Relasi / Constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('discount_id')->references('id')->on('discounts')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
