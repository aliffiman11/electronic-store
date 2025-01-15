<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key for users
            $table->unsignedBigInteger('product_id'); // Foreign key for products
            $table->integer('quantity'); // Number of products
            $table->decimal('price', 10, 2); // Price per product
            $table->decimal('total_price', 10, 2); // Total price (price * quantity)
            $table->string('status')->default('pending'); // Order status
            $table->timestamps(); // Created and updated timestamps

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
