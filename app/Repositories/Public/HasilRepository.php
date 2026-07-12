<?php

namespace App\Repositories\Public;

use App\Models\PengajuanMagang;
use Illuminate\Http\Request;

class HasilRepository
{
    public function index()
    {
        return view('public.hasil.index');
    }

    public function cari(Request $request)
    {

        $request->validate([
            'kode_pengajuan'=>'required'
        ]);

        $pengajuan = PengajuanMagang::with('anggota')
            ->where('kode_pengajuan',$request->kode_pengajuan)
            ->first();

        if(!$pengajuan){

            return response()->json([

                'success'=>false,

                'message'=>'Kode pengajuan tidak ditemukan.'

            ],404);

        }

        return response()->json([

            'success'=>true,

            'data'=>$pengajuan

        ]);

    }
}