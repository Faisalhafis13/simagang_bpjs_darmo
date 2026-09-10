<?php

namespace App\Repositories\Peserta;

use App\Helpers\ActivityLogger;
use App\Models\AnggotaMagang;
use App\Models\Logbook;
use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LogbookRepository
{

    public function index()
    {
        return view('peserta.logbook.index');
    }
    protected function getPengajuanPeserta(): ?PengajuanMagang
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }
        $pengajuanKetua = PengajuanMagang::query()
            ->where('email_ketua', $user->email)
            ->where('status', 'Diterima')
            ->whereNull('archived_at')
            ->latest('id')
            ->first();
        if ($pengajuanKetua) {
            return $pengajuanKetua;
        }
        $anggota = AnggotaMagang::query()
            ->where('email', $user->email)
            ->latest('id')
            ->first();
        if (!$anggota) {
            return null;
        }
        return PengajuanMagang::query()
            ->whereKey($anggota->pengajuan_magang_id)
            ->where('status', 'Diterima')
            ->whereNull('archived_at')
            ->first();
    }
    public function getData()
    {
        $pengajuan = $this->getPengajuanPeserta();
        if (!$pengajuan) {
            return response()->json([
                'status' => 'success',
                'data' => [],
            ]);
        }
        $data = Logbook::query()
            ->where('user_id', Auth::id())
            ->where('pengajuan_magang_id', $pengajuan->id)
            ->latest('tanggal')
            ->latest('id')
            ->get()
            ->map(function ($logbook) {
                return [
                    'id' => $logbook->id,
                    'tanggal' => $logbook->tanggal?->format('Y-m-d'),
                    'aktivitas' => $logbook->aktivitas,
                    'hasil' => $logbook->hasil,
                    'catatan' => $logbook->catatan,
                    'bukti' => $logbook->bukti,
                    'bukti_url' => $logbook->bukti
                        ? Storage::url($logbook->bukti)
                        : null,
                    'status' => $logbook->status ?? 'Menunggu',
                    'catatan_mentor' => $logbook->catatan_mentor,
                    'pengajuan_magang_id' =>
                        $logbook->pengajuan_magang_id,
                ];
            });
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
    
    public function store(Request $request)
    {
        $pengajuan = $this->getPengajuanPeserta();

        if (!$pengajuan) {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Anda tidak memiliki pengajuan magang aktif.',
            ], 422);
        }

        $data = $request->validate([
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string',
            'hasil' => 'required|string',
            'catatan' => 'nullable|string',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $bukti = null;

        if ($request->hasFile('bukti')) {
            $bukti = $request
                ->file('bukti')
                ->store('logbook/bukti', 'public');
        }

        $logbook = Logbook::create([
            'user_id' => Auth::id(),

            'pengajuan_magang_id' => $pengajuan->id,

            'tanggal' => $data['tanggal'],

            'aktivitas' => $data['aktivitas'],

            'hasil' => $data['hasil'],

            'catatan' => $data['catatan'] ?? null,

            'bukti' => $bukti,

            'status' => 'Menunggu',

            'catatan_mentor' => null,
        ]);


        ActivityLogger::log(
            'Logbook',
            'CREATE',
            'Menambah Logbook',
            null,
            $logbook->fresh()->toArray()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Logbook berhasil ditambahkan.',
        ]);
    }


    public function show($id)
    {
        $pengajuan = $this->getPengajuanPeserta();

        if (!$pengajuan) {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Pengajuan magang aktif tidak ditemukan.',
            ], 404);
        }

        $logbook = Logbook::query()
            ->where('user_id', Auth::id())
            ->where('pengajuan_magang_id', $pengajuan->id)
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',

            'data' => [
                'id' => $logbook->id,

                'tanggal' =>
                    $logbook->tanggal?->format('Y-m-d'),

                'aktivitas' =>
                    $logbook->aktivitas,

                'hasil' =>
                    $logbook->hasil,

                'catatan' =>
                    $logbook->catatan,

                'bukti' =>
                    $logbook->bukti,

                'bukti_url' => $logbook->bukti
                    ? Storage::url($logbook->bukti)
                    : null,

                'status' =>
                    $logbook->status ?? 'Menunggu',

                'catatan_mentor' =>
                    $logbook->catatan_mentor,

                'pengajuan_magang_id' =>
                    $logbook->pengajuan_magang_id,
            ],
        ]);
    }


    public function update(Request $request, $id)
    {
        $pengajuan = $this->getPengajuanPeserta();

        if (!$pengajuan) {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Pengajuan magang aktif tidak ditemukan.',
            ], 404);
        }

        $logbook = Logbook::query()
            ->where('user_id', Auth::id())
            ->where('pengajuan_magang_id', $pengajuan->id)
            ->findOrFail($id);


        if (strtolower($logbook->status ?? '') === 'disetujui') {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Logbook sudah disetujui mentor dan tidak dapat diubah lagi.',
            ], 403);
        }

        $oldData = $logbook->toArray();

        $data = $request->validate([
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string',
            'hasil' => 'required|string',
            'catatan' => 'nullable|string',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $updateData = [
            'tanggal' => $data['tanggal'],

            'aktivitas' => $data['aktivitas'],

            'hasil' => $data['hasil'],

            'catatan' => $data['catatan'] ?? null,


            'pengajuan_magang_id' => $pengajuan->id,


            'status' => 'Menunggu',
        ];


        if ($request->hasFile('bukti')) {

            if ($logbook->bukti) {
                Storage::disk('public')
                    ->delete($logbook->bukti);
            }

            $updateData['bukti'] = $request
                ->file('bukti')
                ->store('logbook/bukti', 'public');
        }

        $logbook->update($updateData);


        ActivityLogger::log(
            'Logbook',
            'UPDATE',
            'Mengubah Logbook',
            $oldData,
            $logbook->fresh()->toArray()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Logbook berhasil diperbarui', 
        ]);
    }


    public function destroy($id)
    {
        $pengajuan = $this->getPengajuanPeserta();

        if (!$pengajuan) {
            return response()->json([
                'status' => 'errro',
                'message' =>
                    'Pengajuan magang sudah tidak aktif',
            ], 404);
        }

        $logbook = Logbook::query()
            ->where('user_id', Auth::id())
            ->where('pengajuan_magang_id', $pengajuan->id)
            ->findOrFail($id);


        if (strtolower($logbook->status ?? '') === 'disetujui') {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Logbook sudah disetujui mentor dan tidak dapat dihapus.',
            ], 403);
        }

        $oldData = $logbook->toArray();


        if ($logbook->bukti) {
            Storage::disk('public')
                ->delete($logbook->bukti);
        }

        $logbook->delete();


        ActivityLogger::log(
            'Logbook',
            'DELETE',
            'Menghapus Logbook',
            $oldData,
            null
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Logbook berhasil dihapus.',
        ]);
    }
}