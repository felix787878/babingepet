<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom ini akan menyimpan ID dari ukm_ormawas yang dikelola oleh user dengan role 'pengurus'
            $table->foreignId('manages_ukm_ormawa_id')->nullable()->after('role')
                  ->constrained('ukm_ormawas') // Mereferensi ke tabel ukm_ormawas
                  ->onDelete('set null'); // Jika UkmOrmawa dihapus, set ID ini jadi null di user
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['manages_ukm_ormawa_id']); // Hapus foreign key constraint dulu
            $table->dropColumn('manages_ukm_ormawa_id');    // Baru hapus kolomnya
        });
    }
};