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
        Schema::create('jadwal_pengecualians', function (Blueprint $table) {
            $table->id();
            $table->string('jadwal_kosong_id');
            $table->foreignId('lab_id');
            $table->date('tanggal');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->enum('tipe', ["tambah","hapus"]);
            $table->string('alasan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pengecualians');
    }
};
