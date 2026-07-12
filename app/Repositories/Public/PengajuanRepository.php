<?php

namespace App\Repositories\Public;

use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengajuanRepository
{
    public function index()
    {
        return view('public.pengajuan.index');
    }

public function store(Request $request)
{
    $request->validate([

        'nama_ketua'       => 'required|string|max:255',

        'universitas'      => 'required|string|max:255',

        'semester'         => 'required',

        'no_hp'            => 'required|string|max:20',

        'email_ketua'      => 'required|email',

        'tanggal_mulai'    => 'required|date',

        'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',

        'proposal'         => 'required|mimes:pdf|max:2048',

        'surat_permohonan' => 'required|mimes:pdf|max:2048',

    ]);

$pengajuan = DB::transaction(function () use ($request) {

    $proposal = $request
        ->file('proposal')
        ->store('proposal');

    $surat = $request
        ->file('surat_permohonan')
        ->store('surat_permohonan');

    $pengajuan = PengajuanMagang::create([

        'kode_pengajuan' => 'MAGANG-' . strtoupper(substr(md5(uniqid()),0,8)),

        'nama_ketua' => $request->nama_ketua,

        'universitas' => $request->universitas,

        'semester' => $request->semester,

        'no_hp' => $request->no_hp,

        'email_ketua' => $request->email_ketua,

        'tanggal_mulai' => $request->tanggal_mulai,

        'tanggal_selesai' => $request->tanggal_selesai,

        'proposal' => $proposal,

        'surat_permohonan' => $surat,

        'status' => 'menunggu',

    ]);

    if ($request->anggota) {

        foreach ($request->anggota as $anggota) {

            if (!empty($anggota)) {

                $pengajuan->anggota()->create([

                    'nama_anggota' => $anggota

                ]);

            }

        }

    }

    return $pengajuan;

});

return response()->json([

    'success' => true,

    'message' => 'Pengajuan berhasil dikirim.',

    'kode_pengajuan' => $pengajuan->kode_pengajuan,

]);
}}