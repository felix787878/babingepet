<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UkmApplication extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    // 'ukm_ormawa_name', // Hapus atau komentari jika kolomnya dihapus dari tabel
    // 'ukm_ormawa_slug', // Hapus atau komentari jika kolomnya dihapus dari tabel
    'ukm_ormawa_id',   // Tambahkan ini
    'reason_to_join',
    'skills_experience',
    'phone_contact',
    'status',
];
// ...
// Tambahkan relasi ke UkmOrmawa
public function ukmOrmawa()
{
    return $this->belongsTo(UkmOrmawa::class);
}
public function user()
{
    return $this->belongsTo(User::class);
}
    // Jika Anda punya model UkmOrmawa nanti, Anda bisa buat relasi ke sana juga
    // public function ukmOrmawa() { ... }
}




