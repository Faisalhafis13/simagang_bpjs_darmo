<?php

namespace App\Repositories\BackOffice;

use App\Models\Logbook;
use App\Models\PengajuanMagang;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ArsipPengajuanRepository
{
    public function index()
    {
        return view('back-office.arsip-pengajuan.index');
    }

    public function getData()
    {
        $pengajuans = PengajuanMagang::query()
            ->with([
                'mentor',
                'anggota',
            ])
            ->whereNotNull('archived_at')
            ->latest('archived_at')
            ->get();
        $data = $pengajuans->map(function ($pengajuan) {
            $detail = $this->buildDetailData($pengajuan);
            return array_merge(
                [
                    'id' => $pengajuan->id,
                    'kode_pengajuan' => $pengajuan->kode_pengajuan,
                    'nama_ketua' => $pengajuan->nama_ketua,
                    'email_ketua' => $pengajuan->email_ketua,
                    'no_hp' => $pengajuan->no_hp,
                    'universitas' => $pengajuan->universitas,
                    'semester' => $pengajuan->semester,
                    'tanggal_mulai' => $pengajuan->tanggal_mulai,
                    'tanggal_selesai' => $pengajuan->tanggal_selesai,
                    'status' => $pengajuan->status,
                    'catatan' => $pengajuan->catatan,
                    'archived_at' => $pengajuan->archived_at,
                    'proposal' => $pengajuan->proposal,
                    'surat_permohonan' => $pengajuan->surat_permohonan,
                    'surat_penerimaan' => $pengajuan->surat_penerimaan,
                    'mentor' => $pengajuan->mentor,
                ],
                [
                    'peserta' => $detail['peserta'],
                    'logbooks' => $detail['logbooks'],
                ]
            );
        });
        return response()->json([
            'status' => 'success',
            'data' => $data->values()->all(),
        ]);
    }

    public function detail($id)
    {

        $pengajuan = PengajuanMagang::query()
            ->with([
                'mentor',
                'anggota',
            ])
            ->whereNotNull('archived_at')
            ->findOrFail($id);

        $detail = $this->buildDetailData($pengajuan);

        return response()->json([
            'status' => 'success',

            'data' => [

                'id' => $pengajuan->id,

                'kode_pengajuan' => $pengajuan->kode_pengajuan,

                'nama_ketua' => $pengajuan->nama_ketua,

                'email_ketua' => $pengajuan->email_ketua,

                'no_hp' => $pengajuan->no_hp,

                'universitas' => $pengajuan->universitas,

                'semester' => $pengajuan->semester,

                'tanggal_mulai' => $pengajuan->tanggal_mulai,

                'tanggal_selesai' => $pengajuan->tanggal_selesai,

                'status' => $pengajuan->status,

                'archived_at' => $pengajuan->archived_at,

                'catatan' => $pengajuan->catatan,

                'proposal' => $pengajuan->proposal,

                'surat_permohonan' => $pengajuan->surat_permohonan,

                'surat_penerimaan' => $pengajuan->surat_penerimaan,

                'mentor' => $pengajuan->mentor,

                'peserta' => $detail['peserta'],

                'logbooks' => $detail['logbooks'],
            ],
        ]);
    }

    private function buildDetailData(PengajuanMagang $pengajuan): array
    {

        $anggota = $pengajuan->anggota;

        $emails = collect();

        if (! empty($pengajuan->email_ketua)) {

            $emails->push(
                strtolower(
                    trim($pengajuan->email_ketua)
                )
            );
        }

        foreach ($anggota as $item) {

            if (! empty($item->email)) {

                $emails->push(
                    strtolower(
                        trim($item->email)
                    )
                );
            }
        }

        $emails = $emails
            ->filter()
            ->unique()
            ->values();

        $users = collect();

        if ($emails->isNotEmpty()) {

            $users = User::query()
                ->where(function ($query) use ($emails) {

                    foreach ($emails as $email) {

                        $query->orWhereRaw(
                            'LOWER(TRIM(email)) = ?',
                            [$email]
                        );
                    }

                })
                ->with('mentor')
                ->get();
        }

        $usersByEmail = $users->keyBy(function ($user) {

            return strtolower(
                trim($user->email ?? '')
            );

        });

        $peserta = collect();

        $emailKetua = strtolower(
            trim($pengajuan->email_ketua ?? '')
        );

        $userKetua = null;

        if ($emailKetua !== '') {

            $userKetua =
                $usersByEmail->get($emailKetua);
        }

        $mentorKetua =
            optional($pengajuan->mentor)->nama_mentor
            ?: optional(
                optional($userKetua)->mentor
            )->nama_mentor
            ?: '-';

        $peserta->push([

            'id' => optional($userKetua)->id,

            'nama' => $pengajuan->nama_ketua
                ?: optional($userKetua)->name
                ?: '-',

            'email' => $pengajuan->email_ketua
                ?: optional($userKetua)->email
                ?: '-',

            'no_hp' => $pengajuan->no_hp
                ?: '-',

            'mentor' => $mentorKetua,
        ]);

        foreach ($anggota as $item) {

            $emailAnggota = strtolower(
                trim($item->email ?? '')
            );

            if (
                $emailAnggota !== '' &&
                $emailAnggota === $emailKetua
            ) {
                continue;
            }

            $userAnggota = null;

            if ($emailAnggota !== '') {

                $userAnggota =
                    $usersByEmail->get($emailAnggota);
            }

            $mentorAnggota =
                optional($pengajuan->mentor)->nama_mentor
                ?: optional(
                    optional($userAnggota)->mentor
                )->nama_mentor
                ?: '-';

            $peserta->push([

                'id' => optional($userAnggota)->id,

                'nama' => $item->nama_anggota
                    ?: optional($userAnggota)->name
                    ?: '-',

                'email' => $item->email
                    ?: optional($userAnggota)->email
                    ?: '-',

                'no_hp' => $item->no_hp
                    ?: '-',

                'mentor' => $mentorAnggota,
            ]);
        }

        $userIds = $peserta
            ->pluck('id')
            ->filter()
            ->unique()
            ->values();

        $logbooksQuery = Logbook::query()
            ->with([
                'user',
                'user.mentor',
            ]);

        $logbooksQuery->where(
            'pengajuan_magang_id',
            $pengajuan->id
        );

        $logbooks = $logbooksQuery
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        if ($userIds->isNotEmpty()) {

            $fallbackLogbooks = Logbook::query()
                ->with([
                    'user',
                    'user.mentor',
                ])
                ->whereNull('pengajuan_magang_id')
                ->whereIn(
                    'user_id',
                    $userIds
                )
                ->orderBy('tanggal', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $logbooks = $logbooks
                ->concat($fallbackLogbooks)
                ->sortBy([
                    ['tanggal', 'asc'],
                    ['id', 'asc'],
                ])
                ->unique('id')
                ->values();
        }

        $logbooksData = $logbooks
            ->map(function ($logbook) {

                return [

                    'id' => $logbook->id,

                    'user_id' => $logbook->user_id,

                    'pengajuan_magang_id' => $logbook->pengajuan_magang_id,

                    'tanggal' => $logbook->tanggal,

                    'aktivitas' => $logbook->aktivitas,

                    'hasil' => $logbook->hasil,

                    'catatan' => $logbook->catatan,

                    'bukti' => $logbook->bukti,

                    'status' => $logbook->status,

                    'catatan_mentor' => $logbook->catatan_mentor,

                    'user' => $logbook->user
                        ? [

                            'id' => $logbook->user->id,

                            'name' => $logbook->user->name,

                            'email' => $logbook->user->email,

                            'mentor' => $logbook->user->mentor
                                    ? [
                                        'id' => $logbook->user->mentor->id,

                                        'nama_mentor' => $logbook->user->mentor->nama_mentor,
                                    ]
                                    : null,
                        ]
                        : null,
                ];
            })
            ->values()
            ->all();

        return [

            'peserta' => $peserta
                ->values()
                ->all(),

            'logbooks' => $logbooksData,
        ];
    }

    public function file($id, $type)
    {

        $allowedTypes = [

            'proposal' => [

                'column' => 'proposal',

                'folders' => [

                    'proposal',

                ],
            ],

            'surat-permohonan' => [

                'column' => 'surat_permohonan',

                'folders' => [

                    'surat_permohonan',
                    'surat-permohonan',

                ],
            ],

            'surat-penerimaan' => [

                'column' => 'surat_penerimaan',

                'folders' => [

                    'surat-penerimaan',
                    'surat_penerimaan',

                ],
            ],
        ];

        abort_unless(
            isset($allowedTypes[$type]),
            404,
            'Jenis dokumen tidak valid.'
        );

        $pengajuan =
            PengajuanMagang::findOrFail($id);

        $config =
            $allowedTypes[$type];

        $column =
            $config['column'];

        $databasePath =
            $pengajuan->{$column};

        abort_if(
            empty($databasePath),
            404,
            'Dokumen tidak tersedia.'
        );

        $disk =
            Storage::disk('public');

        $databasePath =
            str_replace(
                '\\',
                '/',
                trim($databasePath)
            );

        $databasePath =
            ltrim(
                $databasePath,
                '/'
            );

        $candidates = [];

        $candidates[] =
            $databasePath;

        if (
            str_starts_with(
                strtolower($databasePath),
                'storage/'
            )
        ) {

            $candidates[] =
                substr(
                    $databasePath,
                    strlen('storage/')
                );
        }

        if (
            str_starts_with(
                strtolower($databasePath),
                'public/'
            )
        ) {

            $candidates[] =
                substr(
                    $databasePath,
                    strlen('public/')
                );
        }

        $basename =
            basename($databasePath);

        foreach ($config['folders'] as $folder) {

            $candidates[] =
                $folder.'/'.$basename;
        }

        $candidates =
            array_values(
                array_unique(
                    array_filter(
                        $candidates
                    )
                )
            );

        $foundPath = null;

        foreach ($candidates as $candidate) {

            $candidate =
                str_replace(
                    '\\',
                    '/',
                    trim($candidate)
                );

            $candidate =
                ltrim(
                    $candidate,
                    '/'
                );

            if (
                str_starts_with(
                    strtolower($candidate),
                    'storage/'
                )
            ) {

                $candidate =
                    substr(
                        $candidate,
                        strlen('storage/')
                    );
            }

            if (
                str_starts_with(
                    strtolower($candidate),
                    'public/'
                )
            ) {

                $candidate =
                    substr(
                        $candidate,
                        strlen('public/')
                    );
            }

            if (
                $disk->exists($candidate)
            ) {

                $foundPath =
                    $candidate;

                break;
            }
        }

        if (! $foundPath) {

            abort(
                404,
                'File dokumen tidak ditemukan.'
            );
        }

        $fullPath =
            $disk->path(
                $foundPath
            );

        $mimeType =
            $disk->mimeType(
                $foundPath
            )
            ?: 'application/octet-stream';

        $fileName =
            basename(
                $foundPath
            );

        return response()->file(
            $fullPath,
            [

                'Content-Type' => $mimeType,

                'Content-Disposition' => 'inline; filename="'.
                    $fileName.
                    '"',
            ]
        );
    }
}
