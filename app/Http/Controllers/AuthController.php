<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // Menampilkan halaman Login
    public function login() {
        return view('login');
    }

    // Memproses data Login
    public function loginPost(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Coba lakukan login
        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            
            // Kunci sesi agar tidak bocor
            $request->session()->regenerate();

            // --- SISTEM PENGATUR LALU LINTAS PINTAR ---
            // Jika yang login adalah Admin, lempar ke Dashboard Admin
            if (\Illuminate\Support\Facades\Auth::user()->role === 'admin') {
                return redirect('/admin/dashboard')->with('success', 'Selamat datang kembali, Admin!');
            }

            // Jika yang login adalah user biasa / mitra, lempar ke Katalog
            return redirect('/katalog');
        }

        // Jika email/password salah, kembalikan dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    // Menampilkan halaman Register
    public function register() {
        return view('register');
    }

    // Memproses data Register
    public function registerPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:4',
            // Validasi file: harus gambar, max 2MB
            'bukti_toko' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' 
        ]);

        try {
            $pathBukti = null;

            // Jika user mencentang mitra dan mengunggah foto
            if ($request->has('is_mitra') && $request->hasFile('bukti_toko')) {
                // Simpan ke folder: storage/app/public/bukti_toko
                $file = $request->file('bukti_toko');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $pathBukti = $file->storeAs('bukti_toko', $namaFile, 'public');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'role' => 'regular',
                'upgradeRequested' => $request->has('is_mitra'),
                'bukti_toko' => $pathBukti // Masukkan path gambar ke database
            ]);

            \Illuminate\Support\Facades\Auth::login($user);
            $request->session()->regenerate();
            
            // --- SISTEM NOTIFIKASI PINTAR ---
            if ($request->has('is_mitra')) {
                // Jika dia mendaftar sebagai teknisi
                return redirect('/katalog')->with('success', 'Berhasil mendaftar! Pengajuan Mitra Teknisi Anda sedang ditinjau oleh Admin.');
            } else {
                // Jika dia pembeli biasa
                return redirect('/katalog')->with('success', 'Pendaftaran berhasil! Selamat datang di SpareParts ID.');
            }
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mendaftar: ' . $e->getMessage()]);
        }
    }

    // ===== Google OAuth =====

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['email', 'profile'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();

        // Prioritas: google_id
        $user = User::where('google_id', $googleId)->first();

        // Fallback aman: email (agar akun yang sudah ada bisa "diikat" google_id-nya)
        if (!$user && $email) {
            $user = User::where('email', $email)->first();
        }

        // Jika belum ada, buat user baru (default: regular)
        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: ($email ? strtok($email, '@') : 'Google User'),
                'email' => $email ?: ($googleId . '@google.local'),
                'password' => Hash::make(\Illuminate\Support\Str::random(32)),
                'role' => 'regular',
                'upgradeRequested' => false,
                'google_id' => $googleId,
            ]);
        } else {
            // Jika user ditemukan lewat email, isi google_id agar login berikutnya konsisten
            if (!$user->google_id) {
                $user->google_id = $googleId;
                $user->save();
            }
        }

        Auth::login($user);
        request()->session()->regenerate();

        if (Auth::user()->role === 'admin') {
            return redirect('/admin/dashboard')->with('success', 'Selamat datang kembali, Admin!');
        }

        return redirect('/katalog');
    }

    // Memproses Logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/katalog');
    }
}
