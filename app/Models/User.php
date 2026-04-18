<?php

namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getFilamentAvatarUrl(): ?string
{
    return $this->avatar ? asset('storage/' . $this->avatar)
        : null;
}
    function isLaboran(){
        return $this->role=='laboran';
    }
    function isMahasiswa()
    {
        return $this->role == 'mahasiswa';
    }
    
    function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }
       function kalab()
    {
        return $this->hasOne(Kalab::class);
    }

    function isKalab()
    {
        return $this->role == 'kepala_laboran';
    }
    function isAdmin()
    {
        return $this->role == 'admin';
    }

  public function labs()
    {
        if ($this->isKalab()) {
 
    return $this->hasOneThrough(
        Lab::class,     // tujuan akhir
        Kalab::class,   // perantara
        'user_id',      // FK di kalabs → users.id
        'kalab_id',     // FK di labs → kalabs.id
        'id',           // PK di users
        'id'            // PK di kalabs
    );
        } elseif ($this->isLaboran()) {
            return $this->hasMany(Lab::class, 'laboran_id');
        }

        return null; // Atau bisa juga mengembalikan koleksi kosong
    }
    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
