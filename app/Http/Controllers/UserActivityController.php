<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon; // Untuk manipulasi tanggal

class UserActivityController extends Controller
{
    public function index()
    {
        // DATA CONTOH - Ganti dengan data dinamis dari database Anda
        // Ambil kegiatan yang diikuti oleh Auth::user()

        $all_my_activities = [
            (object)[
                'id' => 1,
                'name' => 'Workshop Fotografi Dasar: Teknik Komposisi',
                'organizer' => 'UKM Fotografi "Jepret"',
                'date_start' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'date_end' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'time_start' => '10:00',
                'time_end' => '15:00',
                'location' => 'Gedung Kreatif Lt. 3, Ruang Audiovisual',
                'status_keikutsertaan' => 'Terdaftar',
                'type' => 'Workshop',
                'detail_slug' => 'workshop-fotografi-dasar-komposisi',
                'image_url' => 'https://via.placeholder.com/400x200/8A2BE2/FFFFFF?text=Workshop+Fotografi'
            ],
            (object)[
                'id' => 2,
                'name' => 'Pelatihan Intensif Public Speaking',
                'organizer' => 'BEM KEMA Telkom University',
                'date_start' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'date_end' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'time_start' => '08:30',
                'time_end' => '16:00',
                'location' => 'Aula Fakultas Ekonomi dan Bisnis',
                'status_keikutsertaan' => 'Terdaftar',
                'type' => 'Pelatihan',
                'detail_slug' => 'pelatihan-public-speaking-bem',
                'image_url' => 'https://via.placeholder.com/400x200/5F9EA0/FFFFFF?text=Public+Speaking'
            ],
            (object)[
                'id' => 3,
                'name' => 'Pagelaran Seni Akhir Tahun: "Harmoni Nusantara"',
                'organizer' => 'UKM Seni Tari & UKM Musik',
                'date_start' => Carbon::now()->subDays(10)->format('Y-m-d'), // Kegiatan Lampau
                'date_end' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'time_start' => '19:00',
                'time_end' => '22:30',
                'location' => 'Telkom University Convention Hall (TUCH)',
                'status_keikutsertaan' => 'Hadir',
                'type' => 'Pentas Seni',
                'detail_slug' => 'pagelaran-harmoni-nusantara-2025',
                'image_url' => 'https://via.placeholder.com/400x200/FF7F50/FFFFFF?text=Harmoni+Nusantara'
            ],
            (object)[
                'id' => 4,
                'name' => 'Turnamen E-Sport: Mobile Legends Championship',
                'organizer' => 'UKM E-Sport Telkom',
                'date_start' => Carbon::now()->subDays(30)->format('Y-m-d'), // Kegiatan Lampau
                'date_end' => Carbon::now()->subDays(28)->format('Y-m-d'),
                'time_start' => '09:00',
                'time_end' => 'Selesai',
                'location' => 'GSG Telkom University & Online',
                'status_keikutsertaan' => 'Juara 3 Tim "Elang Cyber"',
                'type' => 'Kompetisi E-Sport',
                'detail_slug' => 'mobile-legends-championship-2025',
                'image_url' => 'https://via.placeholder.com/400x200/6495ED/FFFFFF?text=E-Sport+Cup'
            ],
        ];

        $today = Carbon::today()->toDateString();
        $upcoming_activities = [];
        $past_activities = [];

        foreach ($all_my_activities as $activity) {
            // Menggunakan date_end untuk menentukan apakah kegiatan sudah lampau jika merupakan rentang tanggal
            $activity_end_date_for_comparison = $activity->date_end ?? $activity->date_start;
            if ($activity_end_date_for_comparison >= $today) {
                $upcoming_activities[] = $activity;
            } else {
                $past_activities[] = $activity;
            }
        }
        
        // Urutkan kegiatan akan datang berdasarkan tanggal mulai (paling dekat paling atas)
        usort($upcoming_activities, function($a, $b) {
             return strcmp($a->date_start, $b->date_start);
        });

         // Urutkan riwayat kegiatan berdasarkan tanggal mulai (paling baru paling atas)
        usort($past_activities, function($a, $b) {
             return strcmp($b->date_start, $a->date_start);
        });

        return view('my-activities.index', compact('upcoming_activities', 'past_activities'));
    }
}