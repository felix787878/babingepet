<?php

namespace App\Http\Controllers;

use App\Models\UkmApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class UkmOrmawaRegistrationController extends Controller
{
    // PENTING: Data ini harus konsisten dengan UkmOrmawaController
    // Idealnya semua mengambil dari database.
    private function getSharedUkmOrmawaDataSource() {
        // SALIN DATA LENGKAP DARI UkmOrmawaController->getSharedUkmOrmawaDataSource() KE SINI
        // ATAU BUAT SEBUAH HELPER/SERVICE UNTUK DATA INI
        // Contoh singkat (pastikan ini sama dengan yang di UkmOrmawaController):
        return [
            (object)['id' => 1, 'name' => 'UKM Seni Tari "Ancala"', 'type' => 'UKM', 'category' => 'Seni & Budaya', 'logo_url' => 'https://via.placeholder.com/400x250/FFC0CB/000000?text=Seni+Tari+Ancala', 'banner_url' => 'https://via.placeholder.com/1200x400/FFC0CB/333333?text=UKM+Seni+Tari+Ancala+Banner', 'description_short' => 'Wadah ekspresi dan kreativitas mahasiswa Telkom University dalam seni tari tradisional dan modern.', 'description_full' => "Deskripsi lengkap...", 'visi' => 'Visi...', 'misi' => ['Misi 1...'], 'contact_email' => 'ancala.tariku@example.com', 'contact_instagram' => '@ancalatelu', 'is_registration_open' => true, 'slug' => Str::slug('UKM Seni Tari Ancala'), 'registration_deadline_obj' => Carbon::now()->addWeeks(3), 'activities' => [], 'gallery_images' => []],
            (object)['id' => 2, 'name' => 'BEM KEMA Telkom University', 'type' => 'Ormawa', 'category' => 'Organisasi Eksekutif', 'logo_url' => 'https://via.placeholder.com/400x250/ADD8E6/000000?text=BEM+KEMA', 'banner_url' => 'https://via.placeholder.com/1200x400/ADD8E6/333333?text=BEM+KEMA+Banner', 'description_short' => 'Badan Eksekutif Mahasiswa...', 'description_full' => "Deskripsi lengkap...", 'visi' => 'Visi...', 'misi' => ['Misi 1...'], 'contact_email' => 'bemkema@telkomuniversity.ac.id', 'contact_instagram' => '@bemtelkomuniv', 'is_registration_open' => false, 'slug' => Str::slug('BEM KEMA Telkom University'), 'registration_deadline_obj' => null, 'activities' => [], 'gallery_images' => []],
            (object)['id' => 3, 'name' => 'UKM Basket "Warriors"', 'type' => 'UKM', 'category' => 'Olahraga', 'logo_url' => 'https://via.placeholder.com/400x250/FFA07A/000000?text=Basket+Warriors', 'banner_url' => 'https://via.placeholder.com/1200x400/FFA07A/333333?text=UKM+Basket+Banner', 'description_short' => 'Kembangkan skill bola basket...', 'description_full' => "Deskripsi lengkap...", 'visi' => 'Visi...', 'misi' => ['Misi 1...'], 'contact_email' => 'basket@example.com', 'contact_instagram' => '@basketwarriorstelu', 'is_registration_open' => true, 'slug' => Str::slug('UKM Basket Warriors'), 'registration_deadline_obj' => Carbon::now()->addMonth(), 'activities' => [], 'gallery_images' => []],
            (object)['id' => 5, 'name' => 'UKM Fotografi "Lensa Club"', 'type' => 'UKM', 'category' => 'Seni & Media', 'logo_url' => 'https://via.placeholder.com/400x250/C3B1E1/000000?text=Lensa+Club', 'banner_url' => 'https://via.placeholder.com/1200x400/C3B1E1/333333?text=UKM+Lensa+Banner', 'description_short' => 'Komunitas fotografi...', 'description_full' => "Deskripsi lengkap...", 'visi' => 'Visi...', 'misi' => ['Misi 1...'], 'contact_email' => 'lensa@example.com', 'contact_instagram' => '@lensaclubtelu', 'is_registration_open' => true, 'slug' => Str::slug('UKM Fotografi Lensa Club'), 'registration_deadline_obj' => Carbon::now()->addDays(10), 'activities' => [], 'gallery_images' => []],
            (object)['id' => 6, 'name' => 'UKM Debat Bahasa Inggris (TESEDS)', 'type' => 'UKM', 'category' => 'Akademik & Penalaran', 'logo_url' => 'https://via.placeholder.com/400x250/BDB76B/FFFFFF?text=TESEDS+Debate', 'banner_url' => 'https://via.placeholder.com/1200x400/BDB76B/333333?text=UKM+Debat+Banner', 'description_short' => 'Telkom University English Society...', 'description_full' => "Deskripsi lengkap...", 'visi' => 'Visi...', 'misi' => ['Misi 1...'], 'contact_email' => 'teseds@example.com', 'contact_instagram' => '@tesedstelu', 'is_registration_open' => true, 'slug' => Str::slug('UKM Debat Bahasa Inggris TESEDS'), 'registration_deadline_obj' => Carbon::now()->addWeeks(2), 'activities' => [], 'gallery_images' => []],
        ];
    }

    private function findUkmOrmawaBySlug($slug)
    {
        $allUkmOrmawa = $this->getSharedUkmOrmawaDataSource();
        foreach ($allUkmOrmawa as $item) {
            if ($item->slug == $slug) {
                return $item;
            }
        }
        return null;
    }

    public function showApplicationForm($ukm_ormawa_slug)
    {
        $item = $this->findUkmOrmawaBySlug($ukm_ormawa_slug);

        if (!$item) {
            return redirect()->route('ukm-ormawa.index')->with('error', 'UKM/Ormawa tidak ditemukan.');
        }
        if (!isset($item->is_registration_open) || !$item->is_registration_open) {
             return redirect()->route('ukm-ormawa.show', ['slug' => $ukm_ormawa_slug])->with('error', 'Pendaftaran untuk ' . $item->name . ' saat ini sedang ditutup.');
        }

        $existingApplication = UkmApplication::where('user_id', Auth::id())
                                   ->where('ukm_ormawa_slug', $ukm_ormawa_slug)
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
        $item = $this->findUkmOrmawaBySlug($ukm_ormawa_slug);
        if (!$item || !isset($item->is_registration_open) || !$item->is_registration_open) {
            return redirect()->route('ukm-ormawa.index')->with('error', 'Lowongan pendaftaran tidak ditemukan atau sudah ditutup.');
        }

        $existingApplication = UkmApplication::where('user_id', Auth::id())
                                   ->where('ukm_ormawa_slug', $ukm_ormawa_slug)
                                   ->whereIn('status', ['pending', 'approved'])
                                   ->first();
        if ($existingApplication) {
            return redirect()->route('ukm-ormawa.show', ['slug' => $ukm_ormawa_slug])
                             ->with('warning', 'Anda sudah mengirimkan pendaftaran untuk ' . $item->name . '. Mohon tunggu informasi selanjutnya.');
        }

        $validatedData = $request->validate([
            'reason_to_join' => 'required|string|min:20|max:2000',
            'skills_experience' => 'nullable|string|max:2000',
            'phone_contact' => 'required|string|regex:/^08[0-9]{8,12}$/',
            'commitment_checkbox' => 'accepted',
        ],[
            'phone_contact.regex' => 'Format nomor HP tidak valid. Contoh: 081234567890.',
            'commitment_checkbox.accepted' => 'Anda harus menyetujui pernyataan komitmen.'
        ]);

        UkmApplication::create([
            'user_id' => Auth::id(),
            'ukm_ormawa_name' => $item->name,
            'ukm_ormawa_slug' => $ukm_ormawa_slug,
            'reason_to_join' => $validatedData['reason_to_join'],
            'skills_experience' => $validatedData['skills_experience'],
            'phone_contact' => $validatedData['phone_contact'],
            'status' => 'pending',
        ]);

        return redirect()->route('ukm-ormawa.show', ['slug' => $ukm_ormawa_slug])
                         ->with('success', 'Selamat! Pendaftaran Anda untuk ' . $item->name . ' telah berhasil dikirim. Mohon tunggu proses verifikasi dari pengurus.');
    }
}