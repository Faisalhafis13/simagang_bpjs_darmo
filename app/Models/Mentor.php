<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_mentor',
        'divisi',
        'tugas',
    ];

    public function pengajuans()
    {
        return $this->hasMany(PengajuanMagang::class, 'mentor_id');
    }
public function peserta()
{
    return $this->hasMany(
        User::class,
        'mentor_id'
    );
}}

