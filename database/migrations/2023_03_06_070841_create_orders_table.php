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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')->references('id')->on('users');
            $table->string('order_number',100);
            $table->string('transaction_number',100)->nullable();
            $table->string('mobile',15)->nullable();
            $table->string('image')->nullable();
            $table->string('image2')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('price',8,2)->default(0.00);
            $table->decimal('merchant_price',8,2)->default(0.00);
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('orders');
    }
};
