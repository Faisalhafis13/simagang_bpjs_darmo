<?php

namespace App\Repositories\BackOffice;

use App\Models\AnggotaMagang;
use App\Models\Logbook;
use App\Models\PengajuanMagang;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ArsipPengajuanRepository
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('back-office.arsip-pengajuan.index');
    }


    /*
    |--------------------------------------------------------------------------
    | DATA TABLE
    |--------------------------------------------------------------------------
    |
    | Data yang dikirim ke DataTable sudah termasuk:
    |
    | - Data pengajuan
    | - Mentor
    | - Ketua
    | - Anggota
    | - Logbook
    |
    | Jadi ketika tombol Detail diklik,
    | JavaScript bisa langsung membaca:
    |
    | data.peserta
    | data.logbooks
    |
    */

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


        /*
        |--------------------------------------------------------------------------
        | SUSUN DATA
        |--------------------------------------------------------------------------
        */

        $data = $pengajuans->map(function ($pengajuan) {

            $detail = $this->buildDetailData($pengajuan);


            return array_merge(
                [
                    'id' =>
                        $pengajuan->id,

                    'kode_pengajuan' =>
                        $pengajuan->kode_pengajuan,

                    'nama_ketua' =>
                        $pengajuan->nama_ketua,

                    'email_ketua' =>
                        $pengajuan->email_ketua,

                    'no_hp' =>
                        $pengajuan->no_hp,

                    'universitas' =>
                        $pengajuan->universitas,

                    'semester' =>
                        $pengajuan->semester,

                    'tanggal_mulai' =>
                        $pengajuan->tanggal_mulai,

                    'tanggal_selesai' =>
                        $pengajuan->tanggal_selesai,

                    'status' =>
                        $pengajuan->status,

                    'catatan' =>
                        $pengajuan->catatan,

                    'archived_at' =>
                        $pengajuan->archived_at,

                    'proposal' =>
                        $pengajuan->proposal,

                    'surat_permohonan' =>
                        $pengajuan->surat_permohonan,

                    'surat_penerimaan' =>
                        $pengajuan->surat_penerimaan,

                    'mentor' =>
                        $pengajuan->mentor,
                ],

                [
                    'peserta' =>
                        $detail['peserta'],

                    'logbooks' =>
                        $detail['logbooks'],
                ]
            );
        });


        return response()->json([
            'status' => 'success',
            'data' => $data->values()->all(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */

    public function detail($id)
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL PENGAJUAN
        |--------------------------------------------------------------------------
        */

        $pengajuan = PengajuanMagang::query()
            ->with([
                'mentor',
                'anggota',
            ])
            ->whereNotNull('archived_at')
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | BANGUN DATA DETAIL
        |--------------------------------------------------------------------------
        */

        $detail = $this->buildDetailData($pengajuan);


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'status' => 'success',

            'data' => [

                /*
                |------------------------------------------------------------------
                | PENGAJUAN
                |------------------------------------------------------------------
                */

                'id' =>
                    $pengajuan->id,

                'kode_pengajuan' =>
                    $pengajuan->kode_pengajuan,

                'nama_ketua' =>
                    $pengajuan->nama_ketua,

                'email_ketua' =>
                    $pengajuan->email_ketua,

                'no_hp' =>
                    $pengajuan->no_hp,

                'universitas' =>
                    $pengajuan->universitas,

                'semester' =>
                    $pengajuan->semester,

                'tanggal_mulai' =>
                    $pengajuan->tanggal_mulai,

                'tanggal_selesai' =>
                    $pengajuan->tanggal_selesai,

                'status' =>
                    $pengajuan->status,

                'archived_at' =>
                    $pengajuan->archived_at,

                'catatan' =>
                    $pengajuan->catatan,


                /*
                |------------------------------------------------------------------
                | DOKUMEN
                |------------------------------------------------------------------
                */

                'proposal' =>
                    $pengajuan->proposal,

                'surat_permohonan' =>
                    $pengajuan->surat_permohonan,

                'surat_penerimaan' =>
                    $pengajuan->surat_penerimaan,


                /*
                |------------------------------------------------------------------
                | MENTOR
                |------------------------------------------------------------------
                */

                'mentor' =>
                    $pengajuan->mentor,


                /*
                |------------------------------------------------------------------
                | PESERTA
                |------------------------------------------------------------------
                */

                'peserta' =>
                    $detail['peserta'],


                /*
                |------------------------------------------------------------------
                | LOGBOOK
                |------------------------------------------------------------------
                */

                'logbooks' =>
                    $detail['logbooks'],
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD DETAIL DATA
    |--------------------------------------------------------------------------
    |
    | Fungsi ini digunakan oleh:
    |
    | - getData()
    | - detail()
    |
    | Dengan begitu data peserta dan logbook selalu konsisten.
    |
    */

    private function buildDetailData(PengajuanMagang $pengajuan): array
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL ANGGOTA
        |--------------------------------------------------------------------------
        */

        $anggota = $pengajuan->anggota;


        /*
        |--------------------------------------------------------------------------
        | KUMPULKAN EMAIL SEMUA PESERTA
        |--------------------------------------------------------------------------
        |
        | Peserta terdiri dari:
        |
        | 1. Ketua
        | | 2. Anggota
        |
        */

        $emails = collect();


        /*
        |----------------------------------------------------------------------
        | EMAIL KETUA
        |----------------------------------------------------------------------
        */

        if (!empty($pengajuan->email_ketua)) {

            $emails->push(
                strtolower(
                    trim($pengajuan->email_ketua)
                )
            );
        }


        /*
        |----------------------------------------------------------------------
        | EMAIL ANGGOTA
        |----------------------------------------------------------------------
        */

        foreach ($anggota as $item) {

            if (!empty($item->email)) {

                $emails->push(
                    strtolower(
                        trim($item->email)
                    )
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | HILANGKAN DUPLIKAT EMAIL
        |--------------------------------------------------------------------------
        */

        $emails = $emails
            ->filter()
            ->unique()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | AMBIL USER
        |--------------------------------------------------------------------------
        |
        | User dicari berdasarkan email karena:
        |
        | pengajuan_magangs.email_ketua
        | anggota_magangs.email
        |
        | berhubungan dengan:
        |
        | users.email
        |
        */

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


        /*
        |--------------------------------------------------------------------------
        | INDEX USER BERDASARKAN EMAIL
        |--------------------------------------------------------------------------
        */

        $usersByEmail = $users->keyBy(function ($user) {

            return strtolower(
                trim($user->email ?? '')
            );

        });


        /*
        |--------------------------------------------------------------------------
        | PESERTA
        |--------------------------------------------------------------------------
        */

        $peserta = collect();


        /*
        |--------------------------------------------------------------------------
        | KETUA
        |--------------------------------------------------------------------------
        |
        | KETUA WAJIB MASUK.
        |
        | Tidak bergantung kepada tabel anggota_magangs.
        |
        */

        $emailKetua = strtolower(
            trim($pengajuan->email_ketua ?? '')
        );


        $userKetua = null;


        if ($emailKetua !== '') {

            $userKetua =
                $usersByEmail->get($emailKetua);
        }


        /*
        |----------------------------------------------------------------------
        | MENTOR KETUA
        |----------------------------------------------------------------------
        |
        | Prioritas:
        |
        | 1. Mentor pengajuan
        | 2. Mentor user ketua
        |
        */

        $mentorKetua =
            optional($pengajuan->mentor)->nama_mentor
            ?: optional(
                optional($userKetua)->mentor
            )->nama_mentor
            ?: '-';


        /*
        |----------------------------------------------------------------------
        | DATA KETUA
        |----------------------------------------------------------------------
        */

        $peserta->push([

            'id' =>
                optional($userKetua)->id,

            'nama' =>
                $pengajuan->nama_ketua
                ?: optional($userKetua)->name
                ?: '-',

            'email' =>
                $pengajuan->email_ketua
                ?: optional($userKetua)->email
                ?: '-',

            'no_hp' =>
                $pengajuan->no_hp
                ?: '-',

            'mentor' =>
                $mentorKetua,
        ]);


        /*
        |--------------------------------------------------------------------------
        | ANGGOTA
        |--------------------------------------------------------------------------
        */

        foreach ($anggota as $item) {

            $emailAnggota = strtolower(
                trim($item->email ?? '')
            );


            /*
            |------------------------------------------------------------------
            | JIKA EMAIL ANGGOTA SAMA DENGAN KETUA
            |------------------------------------------------------------------
            |
            | Jangan tampilkan dua kali.
            |
            */

            if (
                $emailAnggota !== '' &&
                $emailAnggota === $emailKetua
            ) {
                continue;
            }


            /*
            |------------------------------------------------------------------
            | CARI USER ANGGOTA
            |------------------------------------------------------------------
            */

            $userAnggota = null;


            if ($emailAnggota !== '') {

                $userAnggota =
                    $usersByEmail->get($emailAnggota);
            }


            /*
            |------------------------------------------------------------------
            | MENTOR ANGGOTA
            |------------------------------------------------------------------
            */

            $mentorAnggota =
                optional($pengajuan->mentor)->nama_mentor
                ?: optional(
                    optional($userAnggota)->mentor
                )->nama_mentor
                ?: '-';


            /*
            |------------------------------------------------------------------
            | DATA ANGGOTA
            |------------------------------------------------------------------
            */

            $peserta->push([

                'id' =>
                    optional($userAnggota)->id,

                'nama' =>
                    $item->nama_anggota
                    ?: optional($userAnggota)->name
                    ?: '-',

                'email' =>
                    $item->email
                    ?: optional($userAnggota)->email
                    ?: '-',

                'no_hp' =>
                    $item->no_hp
                    ?: '-',

                'mentor' =>
                    $mentorAnggota,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | USER ID PESERTA
        |--------------------------------------------------------------------------
        */

        $userIds = $peserta
            ->pluck('id')
            ->filter()
            ->unique()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | LOGBOOK
        |--------------------------------------------------------------------------
        |
        | PRIORITAS UTAMA:
        |
        | Semua logbook dengan:
        |
        | pengajuan_magang_id = ID PENGAJUAN
        |
        | Untuk kasus pengajuan 31:
        |
        | Logbook 31
        | Logbook 32
        | Logbook 33
        |
        | semuanya akan masuk.
        |
        */

        $logbooksQuery = Logbook::query()
            ->with([
                'user',
                'user.mentor',
            ]);


        /*
        |--------------------------------------------------------------------------
        | LOGBOOK BERDASARKAN PENGAJUAN
        |--------------------------------------------------------------------------
        */

        $logbooksQuery->where(
            'pengajuan_magang_id',
            $pengajuan->id
        );


        /*
        |--------------------------------------------------------------------------
        | LOGBOOK
        |--------------------------------------------------------------------------
        */

        $logbooks = $logbooksQuery
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | FALLBACK LOGBOOK
        |--------------------------------------------------------------------------
        |
        | Kalau ada logbook lama yang:
        |
        | pengajuan_magang_id = NULL
        |
        | tetapi user_id-nya merupakan peserta pengajuan,
        | tetap ikut ditampilkan.
        |
        */

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


            /*
            |--------------------------------------------------------------------------
            | GABUNGKAN LOGBOOK
            |--------------------------------------------------------------------------
            */

            $logbooks = $logbooks
                ->concat($fallbackLogbooks)
                ->sortBy([
                    ['tanggal', 'asc'],
                    ['id', 'asc'],
                ])
                ->unique('id')
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | SERIALIZE LOGBOOK
        |--------------------------------------------------------------------------
        |
        | Kita bentuk data secara eksplisit supaya JavaScript
        | pasti mendapatkan user.name dan user.email.
        |
        */

        $logbooksData = $logbooks
            ->map(function ($logbook) {

                return [

                    'id' =>
                        $logbook->id,

                    'user_id' =>
                        $logbook->user_id,

                    'pengajuan_magang_id' =>
                        $logbook->pengajuan_magang_id,

                    'tanggal' =>
                        $logbook->tanggal,

                    'aktivitas' =>
                        $logbook->aktivitas,

                    'hasil' =>
                        $logbook->hasil,

                    'catatan' =>
                        $logbook->catatan,

                    'bukti' =>
                        $logbook->bukti,

                    'status' =>
                        $logbook->status,

                    'catatan_mentor' =>
                        $logbook->catatan_mentor,

                    'user' => $logbook->user
                        ? [

                            'id' =>
                                $logbook->user->id,

                            'name' =>
                                $logbook->user->name,

                            'email' =>
                                $logbook->user->email,

                            'mentor' =>
                                $logbook->user->mentor
                                    ? [
                                        'id' =>
                                            $logbook->user->mentor->id,

                                        'nama_mentor' =>
                                            $logbook->user->mentor->nama_mentor,
                                    ]
                                    : null,
                        ]
                        : null,
                ];
            })
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return [

            'peserta' =>
                $peserta
                    ->values()
                    ->all(),

            'logbooks' =>
                $logbooksData,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | FILE
    |--------------------------------------------------------------------------
    */

    public function file($id, $type)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI TYPE
        |--------------------------------------------------------------------------
        */

        $allowedTypes = [

            'proposal' => [

                'column' =>
                    'proposal',

                'folders' => [

                    'proposal',

                ],
            ],

            'surat-permohonan' => [

                'column' =>
                    'surat_permohonan',

                'folders' => [

                    'surat_permohonan',
                    'surat-permohonan',

                ],
            ],

            'surat-penerimaan' => [

                'column' =>
                    'surat_penerimaan',

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


        /*
        |--------------------------------------------------------------------------
        | AMBIL PENGAJUAN
        |--------------------------------------------------------------------------
        */

        $pengajuan =
            PengajuanMagang::findOrFail($id);


        $config =
            $allowedTypes[$type];


        $column =
            $config['column'];


        $databasePath =
            $pengajuan->{$column};


        /*
        |--------------------------------------------------------------------------
        | DATABASE PATH KOSONG
        |--------------------------------------------------------------------------
        */

        abort_if(
            empty($databasePath),
            404,
            'Dokumen tidak tersedia.'
        );


        /*
        |--------------------------------------------------------------------------
        | DISK PUBLIC
        |--------------------------------------------------------------------------
        */

        $disk =
            Storage::disk('public');


        /*
        |--------------------------------------------------------------------------
        | NORMALISASI PATH
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | CANDIDATE PATH
        |--------------------------------------------------------------------------
        */

        $candidates = [];


        /*
        |----------------------------------------------------------------------
        | PATH ASLI
        |----------------------------------------------------------------------
        */

        $candidates[] =
            $databasePath;


        /*
        |----------------------------------------------------------------------
        | HAPUS PREFIX STORAGE/
        |----------------------------------------------------------------------
        */

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


        /*
        |----------------------------------------------------------------------
        | HAPUS PREFIX PUBLIC/
        |----------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | BASENAME
        |--------------------------------------------------------------------------
        */

        $basename =
            basename($databasePath);


        /*
        |--------------------------------------------------------------------------
        | FOLDER SESUAI TYPE
        |--------------------------------------------------------------------------
        */

        foreach ($config['folders'] as $folder) {

            $candidates[] =
                $folder . '/' . $basename;
        }


        /*
        |--------------------------------------------------------------------------
        | HILANGKAN DUPLIKAT
        |--------------------------------------------------------------------------
        */

        $candidates =
            array_values(
                array_unique(
                    array_filter(
                        $candidates
                    )
                )
            );


        /*
        |--------------------------------------------------------------------------
        | CARI FILE
        |--------------------------------------------------------------------------
        */

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


            /*
            |----------------------------------------------------------------------
            | HAPUS STORAGE/
            |----------------------------------------------------------------------
            */

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


            /*
            |----------------------------------------------------------------------
            | HAPUS PUBLIC/
            |----------------------------------------------------------------------
            */

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


            /*
            |----------------------------------------------------------------------
            | CEK FILE
            |----------------------------------------------------------------------
            */

            if (
                $disk->exists($candidate)
            ) {

                $foundPath =
                    $candidate;

                break;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | FILE TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if (!$foundPath) {

            abort(
                404,
                'File dokumen tidak ditemukan.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FULL PATH
        |--------------------------------------------------------------------------
        */

        $fullPath =
            $disk->path(
                $foundPath
            );


        /*
        |--------------------------------------------------------------------------
        | MIME TYPE
        |--------------------------------------------------------------------------
        */

        $mimeType =
            $disk->mimeType(
                $foundPath
            )
            ?: 'application/octet-stream';


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $fileName =
            basename(
                $foundPath
            );


        /*
        |--------------------------------------------------------------------------
        | RETURN FILE
        |--------------------------------------------------------------------------
        */

        return response()->file(
            $fullPath,
            [

                'Content-Type' =>
                    $mimeType,

                'Content-Disposition' =>
                    'inline; filename="' .
                    $fileName .
                    '"',
            ]
        );
    }
}