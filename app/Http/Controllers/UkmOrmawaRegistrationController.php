<?php

namespace App\Http\Controllers;

use App\Models\UkmApplication;
use App\Models\UkmOrmawa; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Str; // Tidak perlu jika slug sudah dihandle model
// use Illuminate\Support\Carbon; // Tidak perlu jika deadline dari model

class UkmOrmawaRegistrationController extends Controller
{
    // HAPUS: getSharedUkmOrmawaDataSource()
    // HAPUS: findUkmOrmawaBySlug($slug)

    public function showApplicationForm($ukm_ormawa_slug)
    {
        $item = UkmOrmawa::where('slug', $ukm_ormawa_slug)->firstOrFail(); // Ambil dari DB

        if (!$item) { // Sebenarnya firstOrFail sudah handle ini
            return redirect()->route('ukm-ormawa.index')->with('error', 'UKM/Ormawa tidak ditemukan.');
        }
        if (!$item->is_registration_open) { // Cek status pendaftaran
             return redirect()->route('ukm-ormawa.show', ['slug' => $ukm_ormawa_slug])->with('error', 'Pendaftaran untuk ' . $item->name . ' saat ini sedang ditutup.');
        }
        // Cek deadline jika ada
        if ($item->registration_deadline && $item->registration_deadline->isPast()) {
            return redirect()->route('ukm-ormawa.show', ['slug' => $ukm_ormawa_slug])->with('error', 'Batas waktu pendaftaran untuk ' . $item->name . ' telah berakhir.');
        }

        $existingApplication = UkmApplication::where('user_id', Auth::id())
                                   ->where('ukm_ormawa_id', $item->id) // Gunakan ukm_ormawa_id
                                   ->whereIn('status', ['pending', 'approved'])
                                   ->first();

        if ($existingApplication) {
            return redirect()->route('ukm-ormawa.show', ['slug' => $ukm_ormawa_slug])
                             ->with('warning', 'Anda sudah mendaftar atau pendaftaran Anda sedang diproses untuk ' . $item->name . '. Status saat ini: ' . ucfirst($existingApplication->status) . '.');
        }

        return view('ukm-ormawa.apply', compact('item'));
    }

    public function submitApplication(Request $request, $ukm_ormawa_slug)
    {
        $item = UkmOrmawa::where('slug', $ukm_ormawa_slug)->firstOrFail(); // Ambil dari DB

        if (!$item || !$item->is_registration_open) {
            return redirect()->route('ukm-ormawa.index')->with('error', 'Lowongan pendaftaran tidak ditemukan atau sudah ditutup.');
        }
        if ($item->registration_deadline && $item->registration_deadline->isPast()) {
            return redirect()->route('ukm-ormawa.show', ['slug' => $ukm_ormawa_slug])->with('error', 'Batas waktu pendaftaran untuk ' . $item->name . ' telah berakhir.');
        }

        $existingApplication = UkmApplication::where('user_id', Auth::id())
                                   ->where('ukm_ormawa_id', $item->id) // Gunakan ukm_ormawa_id
                                   ->whereIn('status', ['pending', 'approved'])
                                   ->first();
        if ($existingApplication) {
            return redirect()->route('ukm-ormawa.show', ['slug' => $ukm_ormawa_slug])
                             ->with('warning', 'Anda sudah mengirimkan pendaftaran untuk ' . $item->name . '. Mohon tunggu informasi selanjutnya.');
        }

        $validatedData = $request->validate([
            'reason_to_join' => 'required|string|min:20|max:2000',
            'skills_experience' => 'nullable|string|max:2000',
            'phone_contact' => 'required|string|regex:/^08[0-9]{8,13}$/', // Regex sedikit diperbaiki
            'commitment_checkbox' => 'accepted',
        ],[
            'phone_contact.regex' => 'Format nomor HP tidak valid. Contoh: 081234567890 (10-15 digit).',
            'commitment_checkbox.accepted' => 'Anda harus menyetujui pernyataan komitmen.'
        ]);

        UkmApplication::create([
            'user_id' => Auth::id(),
            'ukm_ormawa_id' => $item->id, // Simpan ID UKM/Ormawa
            // 'ukm_ormawa_name' => $item->name, // Sebenarnya tidak perlu jika sudah ada relasi via ID
            // 'ukm_ormawa_slug' => $ukm_ormawa_slug, // Sama, tidak perlu jika sudah ada relasi via ID
            'reason_to_join' => $validatedData['reason_to_join'],
            'skills_experience' => $validatedData['skills_experience'],
            'phone_contact' => $validatedData['phone_contact'],
            'status' => 'pending',
        ]);

        return redirect()->route('ukm-ormawa.show', ['slug' => $ukm_ormawa_slug])
                         ->with('success', 'Selamat! Pendaftaran Anda untuk ' . $item->name . ' telah berhasil dikirim. Mohon tunggu proses verifikasi dari pengurus.');
    }
}