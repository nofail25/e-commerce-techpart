<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MitraApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MitraController extends Controller
{
    /**
     * Tampilkan form pendaftaran mitra
     */
    public function create()
    {
        return view('jadi-mitra');
    }

    /**
     * Proses submit form pendaftaran mitra
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            // Data Diri
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'no_whatsapp' => 'nullable|string|max:20',
            
            // Data Usaha
            'nama_toko' => 'nullable|string|max:255',
            'alamat_toko' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            
            // Pengalaman
            'pengalaman_tahun' => 'nullable|integer|min:0|max:50',
            'spesialisasi' => 'nullable|array',
            'spesialisasi.*' => 'string|max:100',
            
            // Dokumen
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sertifikat' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            
            // Informasi tambahan
            'alasan_bergabung' => 'nullable|string',
            'sumber_info' => 'nullable|string|max:100',
        ]);

        try {
            // Cek apakah user sudah pernah mengajukan
            $existingApplication = null;
            if (Auth::check()) {
                $existingApplication = MitraApplication::where('user_id', Auth::id())
                    ->where('status', 'pending')
                    ->first();
            }

            if ($existingApplication) {
                return back()->withErrors(['error' => 'Anda sudah memiliki pengajuan yang masih diproses.']);
            }

            // Upload files
            $fotoTokoPath = null;
            $fotoKtpPath = null;
            $sertifikatPath = null;

            if ($request->hasFile('foto_toko')) {
                $fotoTokoPath = $request->file('foto_toko')->store('mitra_applications/foto_toko', 'public');
            }

            if ($request->hasFile('foto_ktp')) {
                $fotoKtpPath = $request->file('foto_ktp')->store('mitra_applications/foto_ktp', 'public');
            }

            if ($request->hasFile('sertifikat')) {
                $sertifikatPath = $request->file('sertifikat')->store('mitra_applications/sertifikat', 'public');
            }

            // Proses spesialisasi (array ke comma-separated string)
            $spesialisasiString = null;
            if (isset($validated['spesialisasi']) && is_array($validated['spesialisasi'])) {
                $spesialisasiString = implode(', ', $validated['spesialisasi']);
            }

            // Simpan ke database
            $application = MitraApplication::create([
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'no_whatsapp' => $validated['no_whatsapp'] ?? null,
                'nama_toko' => $validated['nama_toko'] ?? null,
                'alamat_toko' => $validated['alamat_toko'] ?? null,
                'kota' => $validated['kota'] ?? null,
                'pengalaman_tahun' => $validated['pengalaman_tahun'] ?? null,
                'spesialisasi' => $spesialisasiString,
                'foto_toko' => $fotoTokoPath,
                'foto_ktp' => $fotoKtpPath,
                'sertifikat' => $sertifikatPath,
                'alasan_bergabung' => $validated['alasan_bergabung'] ?? null,
                'sumber_info' => $validated['sumber_info'] ?? null,
                'status' => 'pending',
            ]);

            // Update user's upgradeRequested flag
            if (Auth::check()) {
                $user = Auth::user();
                $user->upgradeRequested = true;
                if ($fotoTokoPath) {
                    $user->bukti_toko = $fotoTokoPath;
                }
                $user->save();
            }

            return redirect()->route('mitra.status')->with('success', 'Pengajuan Mitra Teknisi berhasil dikirim! Tim kami akan meninjau aplikasi Anda.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengirim pengajuan: ' . $e->getMessage()]);
        }
    }

    /**
     * Tampilkan status pengajuan mitra (hanya untuk user terautentikasi)
     */
    public function status()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan login untuk melihat status pengajuan mitra.');
        }

        $application = MitraApplication::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();

        return view('status-mitra', compact('application'));
    }
}