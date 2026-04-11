<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('peminjaman_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjamans')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->foreignId('jadwal_kosong_id')->nullable(); // referensi jadwal kosong yang dipakai
            $table->timestamps();

            // Unique agar tidak double booking di hari yang sama
            $table->unique(['peminjaman_id', 'tanggal']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('peminjaman_details');
    }
};