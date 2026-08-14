<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [

        'name',

        'email',

        'password',

        'role_id',
          'mentor_id',

        'must_change_password',

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

public function role()
{
    return $this->belongsTo(Role::class);
}

public function mentor()
{
    return $this->belongsTo(Mentor::class);
}

public function logbooks()
{
    return $this->hasMany(Logbook::class);
}
public function pengajuanKetua()
{
    return $this->hasMany(
        PengajuanMagang::class,
        'email_ketua',
        'email'
    );
}
public function anggotaMagang()
{
    return $this->hasMany(
        AnggotaMagang::class,
        'email',
        'email'
    );
}
}
