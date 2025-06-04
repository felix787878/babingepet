<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id(); // Ini sudah mendefinisikan 'id' bigint unsigned auto_increment primary key

            $table->foreignId('ukm_ormawa_id')->constrained('ukm_ormawas')->onDelete('cascade');
            $table->foreignId('user_id')->comment('Pengurus yang membuat/mengelola kegiatan ini')->constrained('users')->onDelete('cascade'); // Atau onDelete('set null') jika pengurus bisa dihapus tapi kegiatan tetap ada
            $table->string('name');
            $table->text('description');
            $table->date('date_start');
            $table->date('date_end')->nullable();
            $table->string('time_start'); // Pertimbangkan menggunakan tipe 'time' jika formatnya konsisten
            $table->string('time_end');   // Pertimbangkan menggunakan tipe 'time'
            $table->string('location');
            $table->string('type'); 
            $table->string('image_banner_url')->nullable();
            $table->boolean('is_published')->default(false);

            // Kolom-kolom ini tidak perlu didefinisikan lagi jika Anda menggunakan $table->id(); dan $table->timestamps();
            // $table->bigIncrements('id'); // DUPLIKAT JIKA $table->id() SUDAH ADA
            // $table->timestamps(); // DUPLIKAT JIKA $table->timestamps() SUDAH ADA DI BAWAH

            $table->timestamps(); // Ini sudah mendefinisikan 'created_at' dan 'updated_at' timestamp nullable
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};