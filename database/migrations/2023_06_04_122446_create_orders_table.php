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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->foreignId('user_id');
            $table->integer('total_price');
            $table->string('model_id')->nullable();
            $table->string('model_type')->nullable();
            $table->string('type');
            $table->integer('total_payment')->nullable();
            $table->enum('payment_status', ['waiting', 'success', 'expired', 'cancel', 'error', 'pending'])->comment('1=menunggu pembayaran, 2=sudah dibayar, 3=kadaluarsa, 4=batal');
            $table->json('payment_data')->nullable();
            $table->json('transaction_data');
            $table->string('payment_method')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->string('snap_token', 36)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
