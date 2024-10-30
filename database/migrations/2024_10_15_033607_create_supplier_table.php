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
        Schema::create('supplier', function (Blueprint $table) {
            $table->id();
            $table->string('nama toko', 255);
            $table->string('alamat', 255);
            $table->string('kota', 255);
            $table->integer('kodepos')->lenght(10);
            $table->string('provinsi', 255);
            $table->string('negara', 255);
            $table->string('deskripsi', 255);
            $table->integer('rating')->lenght(5);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};