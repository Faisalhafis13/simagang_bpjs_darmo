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

    /*
    |--------------------------------------------------------------------------
    | Anggota Kelompok
    |--------------------------------------------------------------------------
    */

    public function anggota()
    {
        return $this->hasMany(
            AnggotaMagang::class,
            'pengajuan_magang_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Mentor Pengajuan
    |--------------------------------------------------------------------------
    */

    public function mentor()
    {
        return $this->belongsTo(
            Mentor::class,
            'mentor_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Logbook Kelompok
    |--------------------------------------------------------------------------
    */

    public function logbooks()
    {
        return $this->hasMany(
            Logbook::class,
            'pengajuan_magang_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scope Pengajuan Aktif
    |--------------------------------------------------------------------------
    |
    | Aktif berarti belum diarsipkan.
    |
    | Jadi Pending, Diterima, dan Ditolak semuanya tetap aktif
    | selama archived_at masih NULL.
    |
    */

    public function scopeAktif($query)
    {
        return $query
            ->whereNull('archived_at');
    }

    /*
    |--------------------------------------------------------------------------
    | Scope Pengajuan Arsip
    |--------------------------------------------------------------------------
    */

    public function scopeArsip($query)
    {
        return $query
            ->whereNotNull('archived_at');
    }

    /*
    |--------------------------------------------------------------------------
    | Scope Pengajuan Ditolak
    |--------------------------------------------------------------------------
    */

    public function scopeDitolak($query)
    {
        return $query
            ->where('status', 'Ditolak');
    }

    /*
    |--------------------------------------------------------------------------
    | Cek Arsip
    |--------------------------------------------------------------------------
    */

    public function isArchived(): bool
    {
        return !is_null($this->archived_at);
    }

    /*
    |--------------------------------------------------------------------------
    | Cek Masa Magang Selesai
    |--------------------------------------------------------------------------
    |
    | Tetap dipertahankan karena mungkin digunakan bagian sistem lain.
    | Tetapi fungsi ini TIDAK lagi digunakan untuk otomatis mengarsipkan.
    |
    */

    public function isMasaMagangSelesai(): bool
    {
        if (!$this->tanggal_selesai) {
            return false;
        }

        return $this->tanggal_selesai->lt(today());
    }

    /*
    |--------------------------------------------------------------------------
    | Cek Pengajuan Ditolak
    |--------------------------------------------------------------------------
    */

    public function isDitolak(): bool
    {
        return strtolower($this->status ?? '') === 'ditolak';
    }

    /*
    |--------------------------------------------------------------------------
    | Cek Pengajuan Diterima
    |--------------------------------------------------------------------------
    */

    public function isDiterima(): bool
    {
        return strtolower($this->status ?? '') === 'diterima';
    }
}