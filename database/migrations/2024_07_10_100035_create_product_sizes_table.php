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
        Schema::create('product_sizes', function (Blueprint $table) {
            $table->integer('prodsize_id', true, true);
            $table->integer('prodsize_product')->unsigned();
            $table->integer('prodsize_size')->unsigned();
            $table->string('prodsize_code', 8);
            $table->decimal('prodsize_cost', 9, 2)->default('0.00');
            $table->decimal('prodsize_sellprice', 9, 2)->default('0.00');
            $table->decimal('prodsize_price', 9, 2)->default('0.00');
            $table->integer('prodsize_qty')->unsigned()->default('0');
            $table->tinyInteger('prodsize_discount')->default('0');
            $table->dateTime('prodsize_discount_start')->nullable();
            $table->dateTime('prodsize_discount_end')->nullable();
            $table->boolean('prodsize_status')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sizes');
    }
};