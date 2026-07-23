<?php

namespace App\Models;

use App\Models\AnggotaMagang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'anggota_magang_id',
        'tanggal',
        'aktivitas',
        'hasil',
        'catatan',
    ];

    protected $dates = [
        'tanggal',
    ];

    public function anggota()
    {
        return $this->belongsTo(AnggotaMagang::class, 'anggota_magang_id');
    }
}
