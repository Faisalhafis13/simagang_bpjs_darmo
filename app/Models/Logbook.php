<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',

        'tanggal',

        'aktivitas',

        'hasil',

        'catatan',

    ];

    protected $casts = [

        'tanggal' => 'date',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}