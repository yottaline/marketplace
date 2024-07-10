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
        Schema::create('product_colors', function (Blueprint $table) {
            $table->integer('prodcolor_id', true, true);
            $table->string('prodcolor_code', 8);
            $table->string('prodcolor_name', 120);
            $table->integer('prodcolor_product')->unsigned();
            $table->integer('prodcolor_media')->unsigned();
            $table->smallInteger('prodcolor_minqty')->default('0');
            $table->smallInteger('prodcolor_maxqty')->default('0');
            $table->boolean('prodcolor_status')->default('0');
            // $table->timestamps();/
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_colors');
    }
};