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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->onDelete('cascade');
            $table->foreignId('mahasiswa_id');
            $table->foreignId('jadwal_kosong_id')->nullable();
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->string('keperluan', 500);
            $table->string('catatan_mahasiswa', 500);
            $table->string('surat_peminjaman', 255);
            $table->enum('status', ["pending","confirmed_laboran","pending_kepala","approved","rejected"])->default('pending');
            $table->dateTime('confirmed_laboran_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->text('catatan_laboran')->nullable();
            $table->text('catatan_kepala')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
