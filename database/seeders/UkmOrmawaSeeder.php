<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UkmOrmawa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UkmOrmawaSeeder extends Seeder
{
    public function run(): void
    {
        UkmOrmawa::updateOrCreate(
            ['name' => 'UKM Tari Saman'],
            [
                'slug' => Str::slug('UKM Tari Saman'),
                'type' => 'UKM',
                'category' => 'Seni & Budaya',
                'description_short' => 'Unit Kegiatan Mahasiswa untuk seni Tari Saman dari Aceh.',
                'description_full' => 'Deskripsi lengkap UKM Tari Saman...',
                'visi' => 'Visi UKM Tari Saman...',
                // HAPUS json_encode() dari sini
                'misi' => ['Misi 1 Saman', 'Misi 2 Saman'],
                'logo_url' => 'images/logos/default_logo.png',
                'banner_url' => 'images/banners/default_banner.png',
                'contact_email' => 'saman@example.com',
                'contact_instagram' => '@ukmsaman_tu',
                'is_registration_open' => true,
                'registration_deadline' => now()->addWeeks(2)->toDateString(),
            ]
        );

        UkmOrmawa::updateOrCreate(
            ['name' => 'UKM Paduan Suara Mahasiswa Harmoni'],
            [
                'slug' => Str::slug('UKM Paduan Suara Mahasiswa Harmoni'),
                'type' => 'UKM',
                'category' => 'Seni & Budaya',
                'description_short' => 'Unit Kegiatan Mahasiswa yang berfokus pada pengembangan bakat dalam bidang tarik suara dan paduan suara.',
                'description_full' => 'Deskripsi lengkap UKM Paduan Suara Mahasiswa Harmoni...',
                'visi' => 'Visi UKM Paduan Suara...',
                // HAPUS json_encode() dari sini
                'misi' => ['Misi 1 Padus', 'Misi 2 Padus'],
                'logo_url' => 'images/logos/default_logo.png',
                'banner_url' => 'images/banners/default_banner.png',
                'contact_email' => 'padus@example.com',
                'contact_instagram' => '@ukmpadus_tu',
                'is_registration_open' => false,
                'registration_deadline' => null,
            ]
        );

        UkmOrmawa::updateOrCreate(
            ['name' => 'BEM Fakultas Rekayasa Industri'],
            [
                'slug' => Str::slug('BEM Fakultas Rekayasa Industri'),
                'type' => 'Ormawa',
                'category' => 'Organisasi Eksekutif',
                'description_short' => 'Badan Eksekutif Mahasiswa tingkat Fakultas Rekayasa Industri, mewadahi aspirasi mahasiswa FRI.',
                'description_full' => 'Deskripsi lengkap BEM FRI...',
                'visi' => 'Visi BEM FRI...',
                // HAPUS json_encode() dari sini
                'misi' => ['Misi 1 BEM FRI', 'Misi 2 BEM FRI'],
                'logo_url' => 'images/logos/default_logo.png',
                'banner_url' => 'images/banners/default_banner.png',
                'contact_email' => 'bemfri@example.com',
                'contact_instagram' => '@bemfri_tu',
                'is_registration_open' => true,
                'registration_deadline' => now()->addMonth()->toDateString(),
            ]
        );

        UkmOrmawa::updateOrCreate(
            ['name' => 'UKM Fotografi Lensa Kampus'],
            [
                'slug' => Str::slug('UKM Fotografi Lensa Kampus'),
                'type' => 'UKM',
                'category' => 'Seni & Media',
                'description_short' => 'Wadah bagi mahasiswa yang memiliki minat dan bakat di bidang fotografi.',
                'description_full' => 'Deskripsi lengkap UKM Fotografi Lensa Kampus...',
                'visi' => 'Visi UKM Fotografi...',
                // HAPUS json_encode() dari sini
                'misi' => ['Misi 1 Fotografi', 'Misi 2 Fotografi'],
                'logo_url' => 'images/logos/default_logo.png',
                'banner_url' => 'images/banners/default_banner.png',
                'contact_email' => 'fotografi@example.com',
                'contact_instagram' => '@ukmfotografi_tu',
                'is_registration_open' => true,
                'registration_deadline' => now()->addDays(10)->toDateString(),
            ]
        );
    }
}