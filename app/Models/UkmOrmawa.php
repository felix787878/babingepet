<?php

namespace App\Models; // Pastikan namespace ini benar

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Pastikan ini di-import

class UkmOrmawa extends Model
{
    use HasFactory;

    // Pastikan 'slug' dan 'misi' ada di sini
    protected $fillable = [
        'pengurus_id', // Tambahkan ini
        'name',
        'slug',
        'type',
        'category',
        'logo_url',
        'banner_url',
        'description_short',
        'description_full',
        'visi',
        'misi', // Ini yang penting untuk casting
        'contact_email',
        'contact_instagram',
        'is_registration_open',
        'registration_deadline',
    ];

    // Pastikan casting 'misi' => 'array' sudah benar
    protected $casts = [
        'misi' => 'array', // Ini adalah kunci masalah Anda
        'is_registration_open' => 'boolean',
        'registration_deadline' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($ukmOrmawa) {
            if (empty($ukmOrmawa->slug)) {
                $ukmOrmawa->slug = Str::slug($ukmOrmawa->name);
            }
        });
        static::updating(function ($ukmOrmawa) {
            // Hanya update slug jika nama berubah DAN slug lama kosong atau sama dengan slug dari nama lama
            // Ini untuk mencegah slug yang sudah di-set manual ter überschreiben secara otomatis
            if ($ukmOrmawa->isDirty('name')) {
                $originalSlug = Str::slug($ukmOrmawa->getOriginal('name'));
                if (empty($ukmOrmawa->getOriginal('slug')) || $ukmOrmawa->getOriginal('slug') === $originalSlug) {
                    $ukmOrmawa->slug = Str::slug($ukmOrmawa->name);
                }
            }
        });
    }

    // Relasi ke Pendaftaran Anggota
    public function applications()
    {
        return $this->hasMany(UkmApplication::class, 'ukm_ormawa_id');
    }

    // Anda bisa menambahkan relasi lain di sini jika perlu
    public function pengurus()
    {
        return $this->belongsTo(User::class, 'pengurus_id');
    }
}