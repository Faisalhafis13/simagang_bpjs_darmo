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
        'surat_penerimaan',
        'status',
        'catatan',
        'mentor_id',
        'archived_at',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'archived_at' => 'datetime',
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
        return $this->belongsTo(
            Mentor::class,
            'mentor_id'
        );
    }


    public function logbooks()
    {
        return $this->hasMany(
            Logbook::class,
            'pengajuan_magang_id'
        );
    }


    public function scopeAktif($query)
    {
        return $query
            ->whereNull('archived_at');
    }


    public function scopeArsip($query)
    {
        return $query
            ->whereNotNull('archived_at');
    }


    public function scopeDitolak($query)
    {
        return $query
            ->where('status', 'Ditolak');
    }


    public function isArchived(): bool
    {
        return !is_null($this->archived_at);
    }


    public function isMasaMagangSelesai(): bool
    {
        if (!$this->tanggal_selesai) {
            return false;
        }

        return $this->tanggal_selesai->lt(today());
    }


    public function isDitolak(): bool
    {
        return strtolower($this->status ?? '') === 'ditolak';
    }


    public function isDiterima(): bool
    {
        return strtolower($this->status ?? '') === 'diterima';
    }
}