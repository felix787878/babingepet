<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->onDelete('cascade');
            $table->foreignId('user_id')->comment('Anggota yang hadir/absen')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Hadir', 'Absen', 'Izin'])->default('Absen');
            $table->text('notes')->nullable()->comment('Catatan jika ada, misal alasan izin');
            $table->timestamps();

            $table->unique(['activity_id', 'user_id']); // Pastikan satu user hanya punya satu status per kegiatan

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_attendances');
    }
};
