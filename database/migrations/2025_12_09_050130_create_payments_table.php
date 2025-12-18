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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('order_id');

        $table->decimal('amount', 12, 2);
        $table->string('payment_status')->default('pending'); // settlement, pending, deny, cancel, expired
        $table->text('snap_token')->nullable();
        $table->timestamp('payment_date')->nullable();

        $table->timestamps();

        // FK
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
