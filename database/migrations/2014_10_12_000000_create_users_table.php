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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username', '20')->unique();
            $table->string('nomor_telp')->unique()->nullable();
            $table->string('image')->nullable();
            $table->string('alamat_ktp')->nullable();
            $table->string('alamat_sekarang')->nullable();
            $table->string('kota')->nullable();
            $table->string('zip')->nullable();
            $table->json('sosmed')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
