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
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('order_id');
        $table->unsignedBigInteger('product_id');

        $table->decimal('weight', 10, 2);
        $table->decimal('price', 12, 2);
        $table->integer('qty');
        $table->decimal('discounts', 12, 2)->default(0);
        $table->decimal('subtotal', 12, 2);

        $table->timestamps();

        // FK
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
