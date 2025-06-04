<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ukm_ormawas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->enum('type', ['UKM', 'Ormawa']);
            $table->string('category');
            $table->string('logo_url')->nullable();
            $table->string('banner_url')->nullable();
            $table->text('description_short')->nullable();
            $table->text('description_full')->nullable();
            $table->text('visi')->nullable();
            $table->json('misi')->nullable(); // Menyimpan misi sebagai JSON array
            $table->string('contact_email')->nullable();
            $table->string('contact_instagram')->nullable();
            $table->boolean('is_registration_open')->default(false);
            $table->date('registration_deadline')->nullable(); // Menggunakan tipe date untuk deadline
            // Kolom ini akan kita tambahkan nanti setelah tabel users dimodifikasi
            // $table->foreignId('pengurus_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ukm_ormawas');
    }
};