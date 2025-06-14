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
        Schema::dropIfExists('orders');

        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->dateTime('order_date');
            $table->foreignId('customer_id')->nullable()->references('id')->on('users');
            $table->foreignId('customer_details_id')->nullable()->references('customer_details_id')->on('customer_details');
            $table->foreignId('card_id')->nullable()->references('card_id')->on('credit_cards');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
