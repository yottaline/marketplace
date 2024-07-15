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
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('orderItem_id', true, true);
            $table->integer('orderItem_order')->unsigned();
            $table->integer('orderItem_product')->unsigned();
            $table->integer('orderItem_size')->unsigned();
            $table->decimal('orderItem_productPrice', 12, 2);
            $table->decimal('orderItem_subtotal', 12, 2);
            $table->integer('orderItem_qty')->unsigned();
            $table->decimal('orderItem_disc', 9, 2);
            $table->decimal('orderItem_total', 12, 2);
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};