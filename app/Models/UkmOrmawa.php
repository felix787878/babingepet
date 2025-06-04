<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 
use Carbon\Carbon; // Untuk casting tanggal

class UkmOrmawa extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengurus_id', 
        'name',
        'slug',
        'type',
        'category',
        'logo_url',
        'banner_url',
        'description_short',
        'description_full',
        'visi',
        'misi', 
        'contact_email',
        'contact_instagram',
        'is_registration_open',
        'registration_deadline',
    ];

    protected $casts = [
        'misi' => 'array', // Sudah benar
        'is_registration_open' => 'boolean',
        'registration_deadline' => 'datetime', // Ganti ke datetime untuk Carbon object
    ];

    // ... (boot method tetap sama) ...
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($ukmOrmawa) {
            if (empty($ukmOrmawa->slug)) {
                $ukmOrmawa->slug = Str::slug($ukmOrmawa->name);
            }
        });
        static::updating(function ($ukmOrmawa) {
            if ($ukmOrmawa->isDirty('name')) {
                $originalSlug = Str::slug($ukmOrmawa->getOriginal('name'));
                if (empty($ukmOrmawa->getOriginal('slug')) || $ukmOrmawa->getOriginal('slug') === $originalSlug) {
                    $ukmOrmawa->slug = Str::slug($ukmOrmawa->name);
                }
            }
        });
    }

    public function applications()
    {
        return $this->hasMany(UkmApplication::class, 'ukm_ormawa_id');
    }

    public function pengurus()
    {
        return $this->belongsTo(User::class, 'pengurus_id');
    }

    public function activities() // Tambahkan relasi ini jika belum ada
    {
        return $this->hasMany(Activity::class);
    }
}