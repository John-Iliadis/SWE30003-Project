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
        Schema::dropIfExists('products');

        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->string('name');
            $table->string('brand');
            $table->text('description')->nullable();
            $table->decimal('price');
            $table->integer('stock');
            $table->string('category_name')->nullable(); // Match data type of referenced column
            $table->foreign('category_name')->references('category_name')->on('categories');
            $table->string('image_url')->nullable();
            $table->timestamps();
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
