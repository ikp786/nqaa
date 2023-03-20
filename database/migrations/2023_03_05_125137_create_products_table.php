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
            $table->string('name',100);
            $table->longText('description')->nullable();
            $table->string('name_ar_qa',100)->nullable();
            $table->longText('description_ar_qa')->nullable();
            $table->decimal('price',8,2)->default(0.00);
            $table->decimal('merchant_price',8,2)->default(0.00);
            $table->enum('status',['active','inactive'])->default('active');
            $table->text('image')->nullable();
            $table->softDeletes();
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
