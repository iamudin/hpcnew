<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanDetail extends Model
{
    protected $fillable = [
        'peminjaman_id', 'tanggal', 'jam_mulai', 'jam_selesai', 'jadwal_kosong_id'
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function jadwalKosong()
    {
        return $this->belongsTo(JadwalKosong::class);
    }
}