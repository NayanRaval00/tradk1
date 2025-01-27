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
        Schema::create('wear_houses', function (Blueprint $table) {
            $table->id();
            $table->string('address_title')->nullable();
            $table->string('sender_name')->nullable();
            $table->text('full_address')->nullable();
            $table->string('phone')->nullable();
            $table->string('pincode')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wear_houses');
    }
};
