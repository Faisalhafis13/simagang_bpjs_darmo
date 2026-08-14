<?php

namespace App\Repositories\BackOffice;

use App\Helpers\ActivityLogger;
use App\Models\PengajuanMagang;
use App\Models\User;
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
     *
     * Mentor masing-masing peserta diambil dari:
     * users.mentor_id -> mentors
     */
    public function getData()
    {
$pengajuan = PengajuanMagang::with([
    'anggota'
])
->where('status', 'Diterima')
->whereNull('archived_at')
->latest()
->get();
        $data = $pengajuan->map(function ($item) {

            /*
            |--------------------------------------------------------------------------
            | Gabungkan ketua + anggota menjadi satu kelompok
            |--------------------------------------------------------------------------
            */

            $peserta = [];

            /*
            |--------------------------------------------------------------------------
            | Ketua
            |--------------------------------------------------------------------------
            |
            | Cari user berdasarkan email ketua.
            | Mentor diambil dari users.mentor_id.
            |
            */

            $ketuaUser = User::with('mentor')
                ->where('email', $item->email_ketua)
                ->first();

            $peserta[] = [
                'nama'   => $item->nama_ketua,
                'email'  => $item->email_ketua,
                'no_hp'  => $item->no_hp,
                'peran'  => 'Ketua',
                'mentor' => $ketuaUser?->mentor?->nama_mentor ?? '-',
            ];

            /*
            |--------------------------------------------------------------------------
            | Anggota
            |--------------------------------------------------------------------------
            |
            | Setiap anggota dicari berdasarkan emailnya sendiri.
            | Jadi masing-masing peserta mendapatkan mentor masing-masing.
            |
            */

            foreach ($item->anggota as $anggota) {

                $anggotaUser = User::with('mentor')
                    ->where('email', $anggota->email)
                    ->first();

                $peserta[] = [
                    'nama'   => $anggota->nama_anggota,
                    'email'  => $anggota->email,
                    'no_hp'  => $anggota->no_hp,
                    'peran'  => 'Anggota',
                    'mentor' => $anggotaUser?->mentor?->nama_mentor ?? '-',
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

                // Jumlah peserta
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
        | Simpan data lama untuk Activity Log
        |--------------------------------------------------------------------------
        */

        $oldData = $pengajuan->toArray();

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

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        ActivityLogger::log(
            'Peserta',
            'UPDATE',
            'Mengupload surat penerimaan kelompok ' . $pengajuan->kode_pengajuan,
            $oldData,
            $pengajuan->fresh()->toArray()
        );

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
        | Simpan data lama untuk Activity Log
        |--------------------------------------------------------------------------
        */

        $oldData = $pengajuan->toArray();

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

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        ActivityLogger::log(
            'Peserta',
            'DELETE',
            'Menghapus surat penerimaan kelompok ' . $pengajuan->kode_pengajuan,
            $oldData,
            $pengajuan->fresh()->toArray()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Surat penerimaan kelompok berhasil dihapus.',
        ]);
    }
}
