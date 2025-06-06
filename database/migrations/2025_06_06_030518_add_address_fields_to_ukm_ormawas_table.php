<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Perintah untuk MENGUBAH tabel ukm_ormawas
        Schema::table('ukm_ormawas', function (Blueprint $table) {
            // Menambahkan kolom-kolom baru setelah kolom 'contact_instagram'
            $table->string('alamat_lengkap')->nullable()->after('contact_instagram');
            $table->string('provinsi')->nullable()->after('alamat_lengkap');
            $table->string('kabkota')->nullable()->after('provinsi');
            $table->string('kecamatan')->nullable()->after('kabkota');
            $table->string('desakel')->nullable()->after('kecamatan');
            $table->string('Maps_link')->nullable()->after('desakel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Perintah untuk membatalkan perubahan (menghapus kolom) jika migrasi di-rollback
        Schema::table('ukm_ormawas', function (Blueprint $table) {
            $table->dropColumn([
                'alamat_lengkap',
                'provinsi',
                'kabkota',
                'kecamatan',
                'desakel',
                'Maps_link'
            ]);
        });
    }
};