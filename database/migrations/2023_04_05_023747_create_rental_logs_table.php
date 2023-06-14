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
        Schema::create('rental_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_id')->unique();
            $table->string('name');
            $table->foreignId('user_id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('duration');
            $table->integer('rental_cost');
            $table->integer('total_payment')->nullable();
            $table->string('payment_method')->nullable();
            $table->enum('payment_status', ['waiting', 'success', 'expired', 'cancel', 'error', 'pending', 'refund'])->comment('1=menunggu pembayaran, 2=sudah dibayar, 3=kadaluarsa, 4=batal');
            $table->string('type')->comment('rent_building, rent_cars, etc...');

            $table->json('transaction_data');
            $table->timestamps();


            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_logs');
    }
};
