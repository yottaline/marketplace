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
        Schema::create('sizes', function (Blueprint $table) {
            $table->integer('size_id', true, true);
            $table->string('size_code', 8);
            $table->string('size_name', 120);
            $table->string('size_sign', 20)->nullable();
            $table->boolean('size_status')->default('1');
            $table->integer('size_subcategory')->unsigned();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sizes');
    }
};