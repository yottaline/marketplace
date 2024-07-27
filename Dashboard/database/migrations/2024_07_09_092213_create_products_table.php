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
            $table->integer('product_id', true, true);
            $table->string('product_code', 15);
            $table->string('product_name', 255);
            $table->string('product_desc', 1024)->nullable();
            $table->integer('product_category')->unsigned();
            $table->integer('product_subcategory')->unsigned();
            $table->integer('product_created_by')->unsigned();
            // $table->timestamps();
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