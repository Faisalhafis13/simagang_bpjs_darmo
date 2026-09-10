<?php

namespace App\Repositories\BackOffice;

use App\Helpers\ActivityLogger;
use App\Models\PengajuanMagang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PesertaRepository
{
    public function index()
    {
        return view('back-office.peserta.index');
    }
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
            $peserta = [];
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
            return [
                'pengajuan_id' => $item->id,
                'kode_pengajuan' => $item->kode_pengajuan,
                'universitas' => $item->universitas,
                'peserta' => $peserta,
                'jumlah_peserta' => count($peserta),
                'status' => $item->status,
                'surat_penerimaan' => $item->surat_penerimaan,
                'surat_penerimaan_nama' => $item->surat_penerimaan
                    ? basename($item->surat_penerimaan)
                    : null,
            ];
        });
        return response()->json([
            'data' => $data->values(),
        ]);
    }
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


        $oldData = $pengajuan->toArray();


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


        $pengajuan->update([
            'surat_penerimaan' => $path,
        ]);


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


        $oldData = $pengajuan->toArray();


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


        $pengajuan->update([
            'surat_penerimaan' => null,
        ]);


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
