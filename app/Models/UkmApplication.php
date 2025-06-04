<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UkmApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ukm_ormawa_id',   // Pastikan ini ada
        'reason_to_join',
        'skills_experience',
        'phone_contact',
        'status',
        // Kolom 'ukm_ormawa_name' dan 'ukm_ormawa_slug' bisa dihapus dari fillable
        // jika Anda sudah menghapusnya dari tabel dan tidak menggunakannya lagi.
        // Jika masih ada di tabel dan diisi sementara, biarkan.
        'ukm_ormawa_name', 
        'ukm_ormawa_slug',
    ];

    public function ukmOrmawa()
    {
        return $this->belongsTo(UkmOrmawa::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}