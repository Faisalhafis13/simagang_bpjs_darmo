<?php

namespace App\Repositories\BackOffice;

use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PesertaRepository
{
    /**
     * Halaman Data Peserta
     */
    public function index()
    {
        return view('back-office.peserta.index');
    }

    /**
     * Data kelompok peserta.
     *
     * 1 PengajuanMagang = 1 kelompok
     * 1 Kelompok = 1 Surat Penerimaan
     */
    public function getData()
    {
        $pengajuan = PengajuanMagang::with('anggota')
            ->where('status', 'Diterima')
            ->latest()
            ->get();

        $data = $pengajuan->map(function ($item) {

            /*
            |--------------------------------------------------------------------------
            | Gabungkan ketua + anggota menjadi satu kelompok
            |--------------------------------------------------------------------------
            */

            $peserta = [];

            // Ketua
            $peserta[] = [
                'nama'  => $item->nama_ketua,
                'email' => $item->email_ketua,
                'no_hp' => $item->no_hp,
                'peran' => 'Ketua',
            ];

            // Anggota
            foreach ($item->anggota as $anggota) {

                $peserta[] = [
                    'nama'  => $anggota->nama_anggota,
                    'email' => $anggota->email,
                    'no_hp' => $anggota->no_hp,
                    'peran' => 'Anggota',
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Data satu kelompok
            |--------------------------------------------------------------------------
            */

            return [

                // ID pengajuan untuk upload/delete surat
                'pengajuan_id' => $item->id,

                // Kode kelompok
                'kode_pengajuan' => $item->kode_pengajuan,

                // Universitas
                'universitas' => $item->universitas,

                // Semua peserta dalam kelompok
                'peserta' => $peserta,

                // Jumlah anggota kelompok
                'jumlah_peserta' => count($peserta),

                // Status
                'status' => $item->status,

                // Surat penerimaan
                'surat_penerimaan' => $item->surat_penerimaan,

                // Nama file surat saja
                'surat_penerimaan_nama' => $item->surat_penerimaan
                    ? basename($item->surat_penerimaan)
                    : null,

            ];
        });

        return response()->json([
            'data' => $data->values(),
        ]);
    }

    /**
     * Upload surat penerimaan.
     *
     * Satu surat berlaku untuk satu kelompok/pengajuan.
     */
    public function uploadSuratPenerimaan(Request $request, $id)
    {
        $request->validate([
            'surat_penerimaan' => [
                'required',
                'file',
                'mimes:pdf',
                'max:5120',
            ],
        ], [
            'surat_penerimaan.required' => 'Silakan pilih surat penerimaan.',
            'surat_penerimaan.file'     => 'File surat tidak valid.',
            'surat_penerimaan.mimes'    => 'Surat penerimaan harus berupa file PDF.',
            'surat_penerimaan.max'      => 'Ukuran surat maksimal 5 MB.',
        ]);

        $pengajuan = PengajuanMagang::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Kalau sebelumnya sudah ada surat,
        | hapus file lama terlebih dahulu.
        |--------------------------------------------------------------------------
        */

        if (
            $pengajuan->surat_penerimaan &&
            Storage::disk('public')->exists(
                $pengajuan->surat_penerimaan
            )
        ) {
            Storage::disk('public')->delete(
                $pengajuan->surat_penerimaan
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan file baru
        |--------------------------------------------------------------------------
        */

        $file = $request->file('surat_penerimaan');

        $filename =
            'surat-penerimaan-' .
            $pengajuan->kode_pengajuan .
            '-' .
            time() .
            '.pdf';

        $path = $file->storeAs(
            'surat-penerimaan',
            $filename,
            'public'
        );

        /*
        |--------------------------------------------------------------------------
        | Simpan path ke pengajuan
        |--------------------------------------------------------------------------
        */

        $pengajuan->update([
            'surat_penerimaan' => $path,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Surat penerimaan kelompok berhasil diupload.',
            'data' => [
                'id' => $pengajuan->id,
                'kode_pengajuan' => $pengajuan->kode_pengajuan,
                'surat_penerimaan' => $path,
            ],
        ]);
    }

    /**
     * Hapus surat penerimaan satu kelompok.
     */
    public function deleteSuratPenerimaan($id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Hapus file dari storage
        |--------------------------------------------------------------------------
        */

        if (
            $pengajuan->surat_penerimaan &&
            Storage::disk('public')->exists(
                $pengajuan->surat_penerimaan
            )
        ) {
            Storage::disk('public')->delete(
                $pengajuan->surat_penerimaan
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Kosongkan kolom surat
        |--------------------------------------------------------------------------
        */

        $pengajuan->update([
            'surat_penerimaan' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Surat penerimaan kelompok berhasil dihapus.',
        ]);
    }
}