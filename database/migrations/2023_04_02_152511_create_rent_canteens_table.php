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
        Schema::create('rent_canteens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rent_id');
            $table->foreignId('canteen_id');
            $table->timestamps();

            $table->foreign('rent_id')->on('trx_rentals')->references('id');
            $table->foreign('canteen_id')->on('master_canteens')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_canteens');
    }
};
