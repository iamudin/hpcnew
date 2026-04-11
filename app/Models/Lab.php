<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_labor',
        'deskripsi',
        'laboran_id',
        'user_id',
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
            'user_id' => 'integer',
        ];
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

     public function jadwal(): HasMany
    {
        return $this->hasMany(JadwalKosong::class);
    }

    public function kalab(): BelongsTo
    {
        return $this->belongsTo(Kalab::class);
    }

    public function laboran(): BelongsTo
    {
        return $this->belongsTo(User::class, 'laboran_id','id');
    }
}
