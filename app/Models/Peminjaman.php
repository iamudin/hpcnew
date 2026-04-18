<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lab_id',
        'mahasiswa_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'keperluan',
        'surat_peminjaman',
        'status',
        'catatan_laboran',
        'catatan_kepala',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'lab_id' => 'integer',
            'mahasiswa_id' => 'integer',
            'tanggal_mulai' => 'datetime',
            'tanggal_selesai' => 'datetime',
            'confirmed_laboran_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }
public function details()
{
    return $this->hasMany(PeminjamanDetail::class);
}
    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
