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
        Schema::create('subcategories', function (Blueprint $table) {
            $table->integer('subcategory_id', true, true);
            $table->string('subcategory_code', 8);
            $table->string('subcategory_name', 120);
            $table->integer('subcategory_category')->unsigned();
            $table->boolean('subcategory_status')->default('1');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategories');
    }
};