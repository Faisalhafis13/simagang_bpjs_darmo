<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    use HasFactory;

    protected $table = 'logbooks';

    protected $fillable = [
        'user_id',
        'pengajuan_magang_id',
        'tanggal',
        'aktivitas',
        'hasil',
        'catatan',
        'bukti',
        'status',
        'catatan_mentor',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | USER / PESERTA
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PENGAJUAN MAGANG
    |--------------------------------------------------------------------------
    */

    public function pengajuan()
    {
        return $this->belongsTo(
            PengajuanMagang::class,
            'pengajuan_magang_id'
        );
    }
}