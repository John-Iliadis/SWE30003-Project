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
        Schema::create('orderline', function (Blueprint $table) {
            $table->bigIncrements('orderline_id');
            $table->foreignId('order_id')->nullable()->references('order_id')->on('orders');
            $table->foreignId('product_id')->nullable()->references('product_id')->on('products');
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orderline');
    }
};
