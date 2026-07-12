<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaMagang extends Model
{
    use HasFactory;

    protected $table = 'anggota_magangs';

    protected $fillable = [

        'pengajuan_magang_id',

        'nama_anggota',

        'email',

        'no_hp',

    ];

    public function pengajuan()
    {
        return $this->belongsTo(
            PengajuanMagang::class,
            'pengajuan_magang_id'
        );
    }
}