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
        Schema::create('retailers', function (Blueprint $table) {
            $table->integer('retailer_id', true, true);
            $table->integer('retailer_user')->unsigned();
            $table->string('retailer_phone', 24);
            $table->string('retailer_store', 120)->nullable();
            $table->string('retailer_logo', 64)->nullable();
            $table->string('retailer_mobile', 24)->nullable();
            $table->string('retailer_note', 1024)->nullable();
            $table->string('retailer_address', 255);
            $table->boolean('retailer_vat')->default('0');
            $table->boolean('retailer_status')->default('0');
            $table->dateTime('retailer_approved')->nullable();
            $table->integer('retailer_approved_by')->unsigned()->nullable();
            $table->dateTime('retailer_login')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retailers');
    }
};