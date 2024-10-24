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
        Schema::create('verificationsupplier', function (Blueprint $table) {
            $table->id();
            $table->string('namatoko')->unique();
            $table->string('kota', 255);
            $table->string('kecamatan', 255);
            $table->integer('kodepos')->lenght(10);
            $table->string('imageKtp', 255);
            $table->string('imageNPWP', 255);
            $table->integer('nik')->lenght(30);
            $table->string('nama', 255);
            $table->string('ttl', 255);
            $table->string('alamat', 255);
            $table->string('kewarganegaraan', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verificationsupplier');
    }
};
