<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UkmOrmawa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PredefinedUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil UKM yang sudah dibuat oleh UkmOrmawaSeeder
        // Pastikan nama UKM di sini SAMA PERSIS dengan yang ada di UkmOrmawaSeeder
        $ukmSaman = UkmOrmawa::where('name', 'UKM Tari Saman')->first();
        $ukmPadus = UkmOrmawa::where('name', 'UKM Paduan Suara Mahasiswa Harmoni')->first();
        $ukmFotografi = UkmOrmawa::where('name', 'UKM Fotografi Lensa Kampus')->first();

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@seputartelkom.ac.id'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'nim' => 'ADMIN001',
                'study_program' => null, // Admin mungkin tidak punya prodi
                'phone_number' => null, // Atau nomor kantor
                'bio' => 'Administrator utama sistem UKM Connect.',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Mahasiswa Biasa
        User::updateOrCreate(
            ['email' => 'mahasiswa.biasa@seputartelkom.ac.id'],
            [
                'name' => 'Mahasiswa Contoh',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'nim' => '102022300001',
                'study_program' => 'S1 Informatika',
                'phone_number' => '081234567890',
                'bio' => 'Mahasiswa biasa yang antusias.',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Pengurus untuk UKM Tari Saman
        if ($ukmSaman) {
            User::updateOrCreate(
                ['email' => 'pengurus.saman@seputartelkom.ac.id'],
                [
                    'name' => 'Pengurus Saman', // Nama disingkat agar mudah diingat
                    'password' => Hash::make('password123'),
                    'role' => 'pengurus',
                    'nim' => 'PGRS001SMN',
                    'study_program' => 'S1 Desain Komunikasi Visual',
                    'phone_number' => '081100000001',
                    'bio' => 'Pengurus aktif UKM Tari Saman.',
                    'email_verified_at' => now(),
                    'manages_ukm_ormawa_id' => $ukmSaman->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        } else {
            $this->command->warn('UKM Tari Saman tidak ditemukan, user pengurus.saman tidak dibuat.');
        }

        // Pengurus untuk UKM Paduan Suara
        if ($ukmPadus) {
            User::updateOrCreate(
                ['email' => 'pengurus.padus@seputartelkom.ac.id'],
                [
                    'name' => 'Pengurus Padus',
                    'password' => Hash::make('password123'),
                    'role' => 'pengurus',
                    'nim' => 'PGRS002PDS',
                    'study_program' => 'S1 Seni Musik',
                    'phone_number' => '081100000002',
                    'bio' => 'Pengurus aktif UKM Paduan Suara Harmoni.',
                    'email_verified_at' => now(),
                    'manages_ukm_ormawa_id' => $ukmPadus->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        } else {
            $this->command->warn('UKM Paduan Suara Mahasiswa Harmoni tidak ditemukan, user pengurus.padus tidak dibuat.');
        }
        
        // Pengurus untuk UKM Fotografi
        if ($ukmFotografi) {
            User::updateOrCreate(
                ['email' => 'pengurus.fotografi@seputartelkom.ac.id'],
                [
                    'name' => 'Pengurus Fotografi',
                    'password' => Hash::make('password123'),
                    'role' => 'pengurus',
                    'nim' => 'PGRS003FOTO',
                    'study_program' => 'S1 Ilmu Komunikasi',
                    'phone_number' => '081100000003',
                    'bio' => 'Pengurus aktif UKM Fotografi Lensa Kampus.',
                    'email_verified_at' => now(),
                    'manages_ukm_ormawa_id' => $ukmFotografi->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        } else {
             $this->command->warn('UKM Fotografi Lensa Kampus tidak ditemukan, user pengurus.fotografi tidak dibuat.');
        }

        // Direktorat
        User::updateOrCreate(
            ['email' => 'direktorat@seputartelkom.ac.id'],
            [
                'name' => 'Staf Direktorat Kemahasiswaan',
                'password' => Hash::make('password123'),
                'role' => 'direktorat',
                'nim' => 'DIR001',
                'study_program' => null,
                'phone_number' => '0227564108', // Contoh nomor kantor
                'bio' => 'Staf resmi dari Direktorat Kemahasiswaan.',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}