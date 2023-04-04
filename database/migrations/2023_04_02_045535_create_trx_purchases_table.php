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
            $table->dateTime('purchase_date');
            $table->decimal('total_cost');
            $table->string('payment_method')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->smallInteger('discount')->nullable();
            $table->tinyText('note')->nullable();
            $table->string('model_id');
            $table->string('model_type');
            $table->enum('status', ['pending', 'waiting', 'success', 'failed', 'shipping']);
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
