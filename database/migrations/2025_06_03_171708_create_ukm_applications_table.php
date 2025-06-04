<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ukm_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID Mahasiswa yang mendaftar
            $table->string('ukm_ormawa_name'); // Nama UKM/Ormawa yang didaftar
            $table->string('ukm_ormawa_slug'); // Slug UKM/Ormawa untuk referensi
            $table->text('reason_to_join');    // Alasan bergabung
            $table->text('skills_experience')->nullable(); // Keahlian/pengalaman (opsional)
            $table->string('phone_contact'); // Nomor HP untuk dihubungi
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status pendaftaran
            $table->timestamps(); // created_at (tanggal submit) dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ukm_applications');
    }
};