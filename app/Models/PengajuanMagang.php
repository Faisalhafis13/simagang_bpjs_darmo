<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanMagang extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_magangs';

    protected $fillable = [

        'kode_pengajuan',

        'nama_ketua',

        'universitas',

        'semester',

        'no_hp',

        'email_ketua',

        'tanggal_mulai',

        'tanggal_selesai',

        'proposal',

        'surat_permohonan',

        'status',

        'catatan',

        'mentor_id',

    ];

    public function anggota()
    {
        return $this->hasMany(
            AnggotaMagang::class,
            'pengajuan_magang_id'
        );
    }

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id');
    }
}