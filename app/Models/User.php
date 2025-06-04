<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim', // Pastikan ini 'nim', bukan 'nim_nip'
        'study_program',
        'phone_number',
        'bio',
        'manages_ukm_ormawa_id',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function managesUkmOrmawa()
    {
        return $this->belongsTo(UkmOrmawa::class, 'manages_ukm_ormawa_id');
    }

    public function ukmApplications()
    {
        return $this->hasMany(UkmApplication::class);
    }

    public function createdUkmOrmawas()
    {
        return $this->hasMany(UkmOrmawa::class, 'pengurus_id');
    }
}