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
            $table->integer('order_id', true, true);
            $table->string('order_code', 12);
            $table->integer('order_customer')->unsigned();
            $table->decimal('order_tax', 9,2)->nullable();
            $table->decimal('order_subtotal', 9, 2);
            $table->tinyInteger('order_discount')->unsigned()->default('0');
            $table->decimal('order_total', 9, 2);
            $table->tinyInteger('order_status')->default('1');
            $table->string('order_note', 1024)->nullable();
            $table->dateTime('order_modified')->nullable();
            $table->dateTime('order_exec')->nullable();
            $table->dateTime('order_approved')->nullable();
            $table->dateTime('order_delivered')->nullable();
            $table->integer('order_create_by')->unsigned();
            $table->dateTime('order_created');
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