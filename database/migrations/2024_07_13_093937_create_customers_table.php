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
        Schema::create('customers', function (Blueprint $table) {
            $table->integer('customer_id', true, true);
            $table->string('customer_code', 8);
            $table->string('customer_name', 255);
            $table->string('customer_password', 255);
            $table->string('customer_email', 120);
            $table->string('customer_phone', 24);
            $table->string('customer_note', 1024)->nullable();
            $table->string('customer_address', 255);
            $table->boolean('customer_status')->default('1');
            $table->dateTime('customer_created');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};