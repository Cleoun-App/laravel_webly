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
        Schema::create('trx_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('total_cost');
            $table->tinyText('note')->nullable();
            $table->smallInteger('discount')->nullable();
            $table->enum('status', ['pending', 'failed', 'success', 'waiting', 'rented']);
            $table->integer('total_payment')->nullable();
            $table->json('payment_data')->nullable();
            $table->string('payment_method')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->dateTime('exp_date');
            $table->string('snap_token')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_purchases');
    }
};
