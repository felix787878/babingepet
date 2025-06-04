<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'ukm_ormawa_id',
        'user_id',
        'name',
        'description',
        'date_start',
        'date_end',
        'time_start',
        'time_end',
        'location',
        'type',
        'image_banner_url',
        'is_published',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
        'is_published' => 'boolean',
    ];

    public function ukmOrmawa()
    {
        return $this->belongsTo(UkmOrmawa::class);
    }

    public function user() // Pengurus yang membuat
    {
        return $this->belongsTo(User::class);
    }
}