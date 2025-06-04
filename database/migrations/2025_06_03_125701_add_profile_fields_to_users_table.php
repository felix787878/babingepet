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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim')->nullable()->unique()->after('email');
            $table->string('study_program')->nullable()->after('nim');
            $table->string('phone_number')->nullable()->after('study_program');
            $table->text('bio')->nullable()->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hati-hati jika kolom tidak ada saat rollback
            if (Schema::hasColumn('users', 'nim')) {
                $table->dropColumn('nim');
            }
            if (Schema::hasColumn('users', 'study_program')) {
                $table->dropColumn('study_program');
            }
            if (Schema::hasColumn('users', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
            if (Schema::hasColumn('users', 'bio')) {
                $table->dropColumn('bio');
            }
        });
    }
};