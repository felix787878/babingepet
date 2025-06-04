<?php

namespace App\Http\Controllers; // Pastikan namespace ini sudah benar

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class RegistrationOpeningController extends Controller
{
    private function getAllUkmOrmawaData() {
        return [
            (object)[
                'id' => 1,
                'name' => 'UKM Seni Tari "Ancala"',
                'type' => 'UKM',
                'category' => 'Seni & Budaya',
                'logo_url' => 'https://via.placeholder.com/400x250/FFC0CB/000000?text=Seni+Tari+Ancala',
                'description_short' => 'Wadah ekspresi dan kreativitas mahasiswa Telkom University dalam seni tari tradisional dan modern.',
                'is_registration_open' => true,
                'slug' => Str::slug('UKM Seni Tari Ancala'),
                // Simpan sebagai objek Carbon atau Y-m-d string
                'registration_deadline_obj' => Carbon::now()->addWeeks(3),
            ],
            (object)[
                'id' => 2,
                'name' => 'BEM KEMA Telkom University',
                'type' => 'Ormawa',
                'category' => 'Organisasi Eksekutif',
                'logo_url' => 'https://via.placeholder.com/400x250/ADD8E6/000000?text=BEM+KEMA',
                'description_short' => 'Badan Eksekutif Mahasiswa Keluarga Mahasiswa Telkom University, bergerak untuk advokasi dan pelayanan mahasiswa.',
                'is_registration_open' => false,
                'slug' => Str::slug('BEM KEMA Telkom University'),
                'registration_deadline_obj' => null, // Tetap null jika tidak ada deadline
            ],
            (object)[
                'id' => 3,
                'name' => 'UKM Basket "Warriors"',
                'type' => 'UKM',
                'category' => 'Olahraga',
                'logo_url' => 'https://via.placeholder.com/400x250/FFA07A/000000?text=Basket+Warriors',
                'description_short' => 'Kembangkan skill bola basket dan raih prestasi bersama UKM Basket Warriors Telkom University.',
                'is_registration_open' => true,
                'slug' => Str::slug('UKM Basket Warriors'),
                'registration_deadline_obj' => Carbon::now()->addMonth(),
            ],
            (object)[
                'id' => 5,
                'name' => 'UKM Fotografi "Lensa Club"',
                'type' => 'UKM',
                'category' => 'Seni & Media',
                'logo_url' => 'https://via.placeholder.com/400x250/C3B1E1/000000?text=Lensa+Club',
                'description_short' => 'Komunitas bagi para pecinta fotografi untuk belajar teknik, hunting foto, dan pameran karya.',
                'is_registration_open' => true,
                'slug' => Str::slug('UKM Fotografi Lensa Club'),
                'registration_deadline_obj' => Carbon::now()->addDays(10),
            ],
             (object)[
                'id' => 6,
                'name' => 'UKM Debat Bahasa Inggris (TESEDS)',
                'type' => 'UKM',
                'category' => 'Akademik & Penalaran',
                'logo_url' => 'https://via.placeholder.com/400x250/BDB76B/FFFFFF?text=TESEDS+Debate',
                'description_short' => 'Telkom University English Society Debate Squad, asah kemampuan debat dan berpikir kritis.',
                'is_registration_open' => true,
                'slug' => Str::slug('UKM Debat Bahasa Inggris TESEDS'),
                'registration_deadline_obj' => Carbon::now()->addWeeks(2),
            ],
        ];
    }

    public function index()
    {
        $allUkmOrmawa = $this->getAllUkmOrmawaData();
        
        $openRegistrations = array_filter($allUkmOrmawa, function ($item) {
            return isset($item->is_registration_open) && $item->is_registration_open === true;
        });

        // Urutkan berdasarkan batas waktu pendaftaran
        usort($openRegistrations, function($a, $b) {
            // Gunakan objek Carbon langsung untuk perbandingan
            $deadlineA = $a->registration_deadline_obj ? $a->registration_deadline_obj->copy()->startOfDay() : Carbon::maxValue();
            $deadlineB = $b->registration_deadline_obj ? $b->registration_deadline_obj->copy()->startOfDay() : Carbon::maxValue();
            return $deadlineA->timestamp <=> $deadlineB->timestamp;
        });

        return view('registration-openings.index', compact('openRegistrations'));
    }
}