<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ukm_applications', function (Blueprint $table) {
            // Tambahkan kolom foreign key baru
            $table->foreignId('ukm_ormawa_id')->after('user_id')->constrained('ukm_ormawas')->onDelete('cascade');

            // Hapus kolom lama (jika sudah tidak dibutuhkan dan data sudah dimigrasikan)
            // Untuk sekarang, kita bisa biarkan dulu atau hapus jika yakin.
            // Jika ingin menghapus, pastikan tidak ada data penting di kolom ini.
            // $table->dropColumn('ukm_ormawa_name');
            // $table->dropColumn('ukm_ormawa_slug');
        });
    }

    public function down(): void
    {
        Schema::table('ukm_applications', function (Blueprint $table) {
            $table->dropForeign(['ukm_ormawa_id']);
            $table->dropColumn('ukm_ormawa_id');

            // Jika Anda menghapus kolom lama di method up(), tambahkan kembali di sini untuk rollback
            // $table->string('ukm_ormawa_name');
            // $table->string('ukm_ormawa_slug');
        });
    }
};