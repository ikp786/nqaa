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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('null');
            $table->string('order_number',100);
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name')->nullable();
            $table->string('product_name_ar_qa')->nullable();
            $table->string('product_description')->nullable();
            $table->string('product_description_ar_qa')->nullable();
            $table->decimal('price',8,2)->default(0.00);
            $table->decimal('merchant_price',8,2)->default(0.00);
            $table->integer('quantity')->default(0);
            $table->longText('address')->nullable();
            $table->enum('address_type',['mosque','home'])->default('home');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
