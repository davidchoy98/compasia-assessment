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

            $table->unsignedBigInteger('type_id')->references('id')->on('product_types');
            $table->unsignedBigInteger('brand_id')->references('id')->on('brands');
            $table->unsignedBigInteger('model_id')->references('id')->on('product_models');
            $table->integer('product_id')->index();
            $table->integer('quantity')->default(0);

            $table->timestamps();
            $table->softDeletes();
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