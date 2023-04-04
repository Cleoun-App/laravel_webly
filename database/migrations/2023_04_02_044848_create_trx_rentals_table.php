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
        Schema::create('trx_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('duration');
            $table->integer('cost');
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'failed', 'success', 'waiting', 'rented']);
            $table->string('payment_method')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_rentals');
    }
};
