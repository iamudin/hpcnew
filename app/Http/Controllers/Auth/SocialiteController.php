<?php

namespace App\Http\Controllers\Auth;    

use App\Http\Controllers\Controller;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect($provider)
    {
        // Untuk mahasiswa, kita bisa tambahkan hd (hosted domain) jika ingin force domain tertentu
        // return Socialite::driver($provider)->with(['hd' => 'student.univ.ac.id'])->redirect();

        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $googleUser = Socialite::driver($provider)->user();

            // Validasi hanya email mahasiswa (sesuaikan domain kampus kamu)
            $allowedDomain = '@gmail.com'; // GANTI dengan domain kampus kamu
            if (!str_ends_with(strtolower($googleUser->getEmail()), $allowedDomain)) {
                return redirect()->route('filament.admin.login')
                    ->with('error', 'Hanya email mahasiswa yang diizinkan login.');
            }

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Buat user baru otomatis (untuk mahasiswa)
                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'role'             => 'mahasiswa', // set role default mahasiswa
                    'password'          => bcrypt('password'), // password random (tidak dipakai)
                    'email_verified_at' => now(),
                    // Tambahkan kolom lain jika ada, contoh:
                    // 'nim'            => null, // bisa diisi manual nanti
                    // 'prodi'          => null,
                ]);
                $user->mahasiswa()->create([
    'nama'     => $googleUser->getName(),
    'nim'      => null,
    'prodi'    => null,
    'semester' => null,
    'nohp'     => null,
]);
            Auth::login($user, remember: true);

            }

            // Login user
            Auth::login($user, remember: true);

            // Redirect ke dashboard Filament
            return redirect()->intended(Filament::getUrl());

        } catch (\Exception $e) {
            return redirect()->route('filament.admin.auth.login')
                ->with('error', 'Terjadi kesalahan saat login dengan Google.');
        }
    }
}