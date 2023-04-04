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
        Schema::create('cert_validation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id');
            $table->foreignId('faculty_id');
            $table->enum('is_copying', ['yes', 'not']);
            $table->smallInteger('total_copies');
            $table->dateTime('cert_date');
            $table->timestamps();

            $table->foreign('purchase_id')->on('trx_purchases')->references('id');
            $table->foreign('faculty_id')->on('master_faculties')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cert_validation');
    }
};
