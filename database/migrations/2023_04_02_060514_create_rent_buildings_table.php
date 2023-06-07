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
        Schema::create('rent_buildings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('rent_id')->unique();
            $table->foreignId('building_id')->unique();
            $table->timestamps();


            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('rent_id')->on('trx_rentals')->references('id')->cascadeOnDelete();
            $table->foreign('building_id')->on('master_buildings')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_buildings');
    }
};
