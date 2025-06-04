<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ukm_ormawas', function (Blueprint $table) {
            $table->foreignId('pengurus_id')->nullable()->after('id') // User ID dari pengurus yang menambah/mengelola
                  ->constrained('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('ukm_ormawas', function (Blueprint $table) {
            $table->dropForeign(['pengurus_id']);
            $table->dropColumn('pengurus_id');
        });
    }
};