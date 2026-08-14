<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Logbook;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ExportController extends Controller
{
    public function pengajuan(Request $request)
    {
        $query = PengajuanMagang::with('anggota', 'mentor')
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | Filter Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Universitas
        |--------------------------------------------------------------------------
        */

        if ($request->filled('universitas')) {
            $query->where('universitas', $request->universitas);
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Tanggal Mulai
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tanggal_mulai_dari')) {
            $query->whereDate(
                'tanggal_mulai',
                '>=',
                $request->tanggal_mulai_dari
            );
        }

        if ($request->filled('tanggal_mulai_sampai')) {
            $query->whereDate(
                'tanggal_mulai',
                '<=',
                $request->tanggal_mulai_sampai
            );
        }

        $pengajuan = $query->get();

        /*
        |--------------------------------------------------------------------------
        | Buat Spreadsheet
        |--------------------------------------------------------------------------
        */

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Pengajuan Magang');

        /*
        |--------------------------------------------------------------------------
        | Header
        |--------------------------------------------------------------------------
        */

        $headers = [
            'No',
            'Kode Pengajuan',
            'Nama Ketua',
            'Email Ketua',
            'No. HP',
            'Universitas',
            'Semester',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status',
            'Mentor',
            'Jumlah Anggota',
            'Catatan',
        ];

        $columns = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
        ];

        foreach ($headers as $index => $header) {
            $sheet->setCellValue(
                $columns[$index] . '1',
                $header
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Style Header
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A1:M1')
            ->getFont()
            ->setBold(true);

        /*
        |--------------------------------------------------------------------------
        | Data
        |--------------------------------------------------------------------------
        */

        $row = 2;

        foreach ($pengajuan as $index => $item) {

            $sheet->setCellValue("A{$row}", $index + 1);

            $sheet->setCellValue(
                "B{$row}",
                $item->kode_pengajuan
            );

            $sheet->setCellValue(
                "C{$row}",
                $item->nama_ketua
            );

            $sheet->setCellValue(
                "D{$row}",
                $item->email_ketua
            );

            $sheet->setCellValue(
                "E{$row}",
                $item->no_hp
            );

            $sheet->setCellValue(
                "F{$row}",
                $item->universitas
            );

            $sheet->setCellValue(
                "G{$row}",
                $item->semester
            );

            $sheet->setCellValue(
                "H{$row}",
                $item->tanggal_mulai
            );

            $sheet->setCellValue(
                "I{$row}",
                $item->tanggal_selesai
            );

            $sheet->setCellValue(
                "J{$row}",
                $item->status
            );

            $sheet->setCellValue(
                "K{$row}",
                $item->mentor?->nama
            );

            $sheet->setCellValue(
                "L{$row}",
                $item->anggota->count()
            );

            $sheet->setCellValue(
                "M{$row}",
                $item->catatan
            );

            $row++;
        }

        /*
        |--------------------------------------------------------------------------
        | Auto Size Kolom
        |--------------------------------------------------------------------------
        */

        foreach ($columns as $column) {
            $sheet->getColumnDimension($column)
                ->setAutoSize(true);
        }

        /*
        |--------------------------------------------------------------------------
        | Nama File
        |--------------------------------------------------------------------------
        */

        $filename =
            'pengajuan-magang-' .
            now()->format('Y-m-d-H-i-s') .
            '.xlsx';

        /*
        |--------------------------------------------------------------------------
        | Download Excel
        |--------------------------------------------------------------------------
        */

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            $filename,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }
    public function peserta(Request $request)
{
    $pengajuan = \App\Models\PengajuanMagang::with([
        'anggota'
    ])
    ->where('status', 'Diterima')
    ->latest()
    ->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setTitle('Data Peserta');

    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    $headers = [
        'No',
        'Kode Pengajuan',
        'Perguruan Tinggi',
        'Nama Peserta',
        'Email',
        'Nomor HP',
        'Peran',
        'Mentor',
        'Status',
        'Surat Penerimaan',
    ];

    foreach ($headers as $index => $header) {

        $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
            $index + 1
        );

        $sheet->setCellValue(
            $column . '1',
            $header
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Style Header
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle('A1:J1')
        ->getFont()
        ->setBold(true);

    $sheet->getStyle('A1:J1')
        ->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

    $row = 2;
    $no = 1;

    foreach ($pengajuan as $item) {

        /*
        |--------------------------------------------------------------------------
        | Ketua
        |--------------------------------------------------------------------------
        */

        $ketuaUser = \App\Models\User::with('mentor')
            ->where('email', $item->email_ketua)
            ->first();

        $sheet->setCellValue(
            'A' . $row,
            $no
        );

        $sheet->setCellValue(
            'B' . $row,
            $item->kode_pengajuan
        );

        $sheet->setCellValue(
            'C' . $row,
            $item->universitas
        );

        $sheet->setCellValue(
            'D' . $row,
            $item->nama_ketua
        );

        $sheet->setCellValue(
            'E' . $row,
            $item->email_ketua
        );

        $sheet->setCellValue(
            'F' . $row,
            $item->no_hp
        );

        $sheet->setCellValue(
            'G' . $row,
            'Ketua'
        );

        $sheet->setCellValue(
            'H' . $row,
            $ketuaUser?->mentor?->nama_mentor ?? '-'
        );

        $sheet->setCellValue(
            'I' . $row,
            $item->status
        );

        $sheet->setCellValue(
            'J' . $row,
            $item->surat_penerimaan
                ? 'Tersedia'
                : 'Belum tersedia'
        );

        $row++;
        $no++;

        /*
        |--------------------------------------------------------------------------
        | Anggota
        |--------------------------------------------------------------------------
        */

        foreach ($item->anggota as $anggota) {

            $anggotaUser = \App\Models\User::with('mentor')
                ->where('email', $anggota->email)
                ->first();

            $sheet->setCellValue(
                'A' . $row,
                $no
            );

            $sheet->setCellValue(
                'B' . $row,
                $item->kode_pengajuan
            );

            $sheet->setCellValue(
                'C' . $row,
                $item->universitas
            );

            $sheet->setCellValue(
                'D' . $row,
                $anggota->nama_anggota
            );

            $sheet->setCellValue(
                'E' . $row,
                $anggota->email
            );

            $sheet->setCellValue(
                'F' . $row,
                $anggota->no_hp
            );

            $sheet->setCellValue(
                'G' . $row,
                'Anggota'
            );

            $sheet->setCellValue(
                'H' . $row,
                $anggotaUser?->mentor?->nama_mentor ?? '-'
            );

            $sheet->setCellValue(
                'I' . $row,
                $item->status
            );

            $sheet->setCellValue(
                'J' . $row,
                $item->surat_penerimaan
                    ? 'Tersedia'
                    : 'Belum tersedia'
            );

            $row++;
            $no++;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Auto Size
    |--------------------------------------------------------------------------
    */

    foreach (range('A', 'J') as $column) {

        $sheet->getColumnDimension($column)
            ->setAutoSize(true);
    }

    /*
    |--------------------------------------------------------------------------
    | Freeze Header
    |--------------------------------------------------------------------------
    */

    $sheet->freezePane('A2');

    /*
    |--------------------------------------------------------------------------
    | Filter
    |--------------------------------------------------------------------------
    */

    $sheet->setAutoFilter(
        'A1:J' . max(1, $row - 1)
    );

    /*
    |--------------------------------------------------------------------------
    | Download
    |--------------------------------------------------------------------------
    */

    $filename =
        'data-peserta-' .
        now()->format('Y-m-d-His') .
        '.xlsx';

    $writer =
        new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
            $spreadsheet
        );

    return response()->streamDownload(
        function () use ($writer) {

            $writer->save('php://output');

        },
        $filename,
        [
            'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]
    );
}
/**
 * Export data Perguruan Tinggi ke Excel
 */
public function perguruanTinggi(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Ambil seluruh pengajuan
    |--------------------------------------------------------------------------
    |
    | Aktif / nonaktif ditentukan berdasarkan archived_at:
    |
    | archived_at = NULL     -> Aktif
    | archived_at tidak NULL -> Nonaktif
    |
    */

    $pengajuans = \App\Models\PengajuanMagang::with('anggota')
        ->latest()
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Kelompokkan berdasarkan Perguruan Tinggi
    |--------------------------------------------------------------------------
    */

    $universitas = [];


    foreach ($pengajuans as $pengajuan) {

        $key = trim($pengajuan->universitas);

        if ($key === '') {
            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | Buat data universitas jika belum ada
        |--------------------------------------------------------------------------
        */

        if (!isset($universitas[$key])) {

            $universitas[$key] = [

                'universitas' => $key,

                'pengajuan_aktif' => 0,

                'pengajuan_nonaktif' => 0,

                'pengajuan_count' => 0,

                'peserta_aktif' => 0,

                'peserta_nonaktif' => 0,

                'peserta_count' => 0,

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Tentukan status arsip
        |--------------------------------------------------------------------------
        */

        $isAktif = is_null($pengajuan->archived_at);


        /*
        |--------------------------------------------------------------------------
        | Jumlah Peserta
        |--------------------------------------------------------------------------
        |
        | Ketua dihitung sebagai 1 peserta
        | + seluruh anggota kelompok
        |
        */

        $jumlahPeserta = 1 + $pengajuan->anggota->count();


        /*
        |--------------------------------------------------------------------------
        | Hitung Pengajuan & Peserta
        |--------------------------------------------------------------------------
        */

        if ($isAktif) {

            // Pengajuan aktif
            $universitas[$key]['pengajuan_aktif']++;

            // Peserta aktif
            $universitas[$key]['peserta_aktif'] += $jumlahPeserta;

        } else {

            // Pengajuan nonaktif
            $universitas[$key]['pengajuan_nonaktif']++;

            // Peserta nonaktif
            $universitas[$key]['peserta_nonaktif'] += $jumlahPeserta;

        }


        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        $universitas[$key]['pengajuan_count']++;

        $universitas[$key]['peserta_count'] += $jumlahPeserta;
    }


    $data = array_values($universitas);


    /*
    |--------------------------------------------------------------------------
    | Buat Spreadsheet
    |--------------------------------------------------------------------------
    */

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setTitle('Perguruan Tinggi');


    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    $headers = [

        'No',

        'Perguruan Tinggi',

        'Pengajuan Aktif',

        'Pengajuan Nonaktif',

        'Total Pengajuan',

        'Peserta Aktif',

        'Peserta Nonaktif',

        'Total Peserta',

    ];


    foreach ($headers as $index => $header) {

        $column =
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                $index + 1
            );

        $sheet->setCellValue(
            $column . '1',
            $header
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

    $row = 2;


    foreach ($data as $index => $item) {

        /*
        | No
        */

        $sheet->setCellValue(
            'A' . $row,
            $index + 1
        );


        /*
        | Perguruan Tinggi
        */

        $sheet->setCellValue(
            'B' . $row,
            $item['universitas']
        );


        /*
        | Pengajuan Aktif
        */

        $sheet->setCellValue(
            'C' . $row,
            $item['pengajuan_aktif']
        );


        /*
        | Pengajuan Nonaktif
        */

        $sheet->setCellValue(
            'D' . $row,
            $item['pengajuan_nonaktif']
        );


        /*
        | Total Pengajuan
        */

        $sheet->setCellValue(
            'E' . $row,
            $item['pengajuan_count']
        );


        /*
        | Peserta Aktif
        */

        $sheet->setCellValue(
            'F' . $row,
            $item['peserta_aktif']
        );


        /*
        | Peserta Nonaktif
        */

        $sheet->setCellValue(
            'G' . $row,
            $item['peserta_nonaktif']
        );


        /*
        | Total Peserta
        */

        $sheet->setCellValue(
            'H' . $row,
            $item['peserta_count']
        );


        $row++;
    }


    /*
    |--------------------------------------------------------------------------
    | Style Header
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle('A1:H1')
        ->getFont()
        ->setBold(true);


    $sheet->getStyle('A1:H1')
        ->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );


    $sheet->getStyle('A1:H1')
        ->getAlignment()
        ->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        );


    /*
    |--------------------------------------------------------------------------
    | Border
    |--------------------------------------------------------------------------
    */

    if ($row > 2) {

        $sheet->getStyle(
            'A1:H' . ($row - 1)
        )->getBorders()->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Alignment
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle(
        'A2:A' . max(2, $row - 1)
    )->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );


    $sheet->getStyle(
        'C2:H' . max(2, $row - 1)
    )->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );


    /*
    |--------------------------------------------------------------------------
    | Auto Width
    |--------------------------------------------------------------------------
    */

    foreach (range('A', 'H') as $column) {

        $sheet->getColumnDimension($column)
            ->setAutoSize(true);
    }


    /*
    |--------------------------------------------------------------------------
    | Freeze Header
    |--------------------------------------------------------------------------
    */

    $sheet->freezePane('A2');


    /*
    |--------------------------------------------------------------------------
    | Auto Filter
    |--------------------------------------------------------------------------
    */

    if ($row > 2) {

        $sheet->setAutoFilter(
            'A1:H' . ($row - 1)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Download
    |--------------------------------------------------------------------------
    */

    $filename =
        'data-perguruan-tinggi-' .
        now()->format('Y-m-d-His') .
        '.xlsx';


    $writer =
        new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
            $spreadsheet
        );


    return response()->streamDownload(
        function () use ($writer) {

            $writer->save('php://output');

        },
        $filename,
        [
            'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]
    );
}
/**
 * Export data Mentor ke Excel
 */
public function mentor(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Ambil Data Mentor
    |--------------------------------------------------------------------------
    */

    $mentors = \App\Models\Mentor::with('peserta')
        ->latest()
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Buat Spreadsheet
    |--------------------------------------------------------------------------
    */

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setTitle('Data Mentor');

    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    $headers = [
        'No',
        'Nama Mentor',
        'Divisi',
        'Jumlah Peserta',
        'Nama Peserta',
    ];

    foreach ($headers as $index => $header) {

        $column =
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                $index + 1
            );

        $sheet->setCellValue(
            $column . '1',
            $header
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

    $row = 2;

    foreach ($mentors as $index => $mentor) {

        $pesertaNames = $mentor->peserta
            ->pluck('name')
            ->filter()
            ->values()
            ->toArray();

        $namaPeserta = !empty($pesertaNames)
            ? implode(', ', $pesertaNames)
            : '-';

        $sheet->setCellValue(
            'A' . $row,
            $index + 1
        );

        $sheet->setCellValue(
            'B' . $row,
            $mentor->nama_mentor
        );

        $sheet->setCellValue(
            'C' . $row,
            $mentor->divisi
        );

        $sheet->setCellValue(
            'D' . $row,
            $mentor->peserta->count()
        );

        $sheet->setCellValue(
            'E' . $row,
            $namaPeserta
        );

        $row++;
    }

    /*
    |--------------------------------------------------------------------------
    | Style Header
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle('A1:E1')
        ->getFont()
        ->setBold(true);

    $sheet->getStyle('A1:E1')
        ->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    $sheet->getStyle('A1:E1')
        ->getAlignment()
        ->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        );

    /*
    |--------------------------------------------------------------------------
    | Border
    |--------------------------------------------------------------------------
    */

    if ($row > 2) {

        $sheet->getStyle(
            'A1:E' . ($row - 1)
        )->getBorders()->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Alignment
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle(
        'A2:A' . max(2, $row - 1)
    )->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    $sheet->getStyle(
        'D2:D' . max(2, $row - 1)
    )->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    /*
    |--------------------------------------------------------------------------
    | Wrap Text Nama Peserta
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle(
        'E2:E' . max(2, $row - 1)
    )->getAlignment()
        ->setWrapText(true);

    /*
    |--------------------------------------------------------------------------
    | Auto Width
    |--------------------------------------------------------------------------
    */

    foreach (range('A', 'E') as $column) {

        $sheet->getColumnDimension($column)
            ->setAutoSize(true);
    }

    /*
    |--------------------------------------------------------------------------
    | Freeze Header
    |--------------------------------------------------------------------------
    */

    $sheet->freezePane('A2');

    /*
    |--------------------------------------------------------------------------
    | Auto Filter
    |--------------------------------------------------------------------------
    */

    if ($row > 2) {

        $sheet->setAutoFilter(
            'A1:E' . ($row - 1)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Download
    |--------------------------------------------------------------------------
    */

    $filename =
        'data-mentor-' .
        now()->format('Y-m-d-His') .
        '.xlsx';

    $writer =
        new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
            $spreadsheet
        );

    return response()->streamDownload(
        function () use ($writer) {

            $writer->save('php://output');

        },
        $filename,
        [
            'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]
    );
}
/**
 * Export Monitoring Logbook ke Excel
 */
public function logbook(Request $request)
{
    $entries = \App\Models\Logbook::with([
        'user.role',
        'user.mentor',
    ])
        ->orderByDesc('tanggal')
        ->orderByDesc('id')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Buat Spreadsheet
    |--------------------------------------------------------------------------
    */

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setTitle('Monitoring Logbook');

    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    $headers = [
        'No',
        'Tanggal',
        'Nama Peserta',
        'Email',
        'Mentor',
        'Aktivitas',
        'Hasil',
        'Catatan Peserta',
        'Bukti Kegiatan',
        'Status',
        'Catatan Mentor',
    ];

    foreach ($headers as $index => $header) {

        $column =
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                $index + 1
            );

        $sheet->setCellValue(
            $column . '1',
            $header
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Style Header
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle('A1:K1')
        ->getFont()
        ->setBold(true);

    $sheet->getStyle('A1:K1')
        ->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    $sheet->getStyle('A1:K1')
        ->getAlignment()
        ->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        );

    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

    $row = 2;

    foreach ($entries as $index => $entry) {

        $sheet->setCellValue(
            'A' . $row,
            $index + 1
        );

        $sheet->setCellValue(
            'B' . $row,
            $entry->tanggal
                ? $entry->tanggal->format('Y-m-d')
                : '-'
        );

        $sheet->setCellValue(
            'C' . $row,
            $entry->user?->name ?? '-'
        );

        $sheet->setCellValue(
            'D' . $row,
            $entry->user?->email ?? '-'
        );

        $sheet->setCellValue(
            'E' . $row,
            $entry->user?->mentor?->nama_mentor ?? '-'
        );

        $sheet->setCellValue(
            'F' . $row,
            $entry->aktivitas ?? '-'
        );

        $sheet->setCellValue(
            'G' . $row,
            $entry->hasil ?? '-'
        );

        $sheet->setCellValue(
            'H' . $row,
            $entry->catatan ?? '-'
        );

        $sheet->setCellValue(
            'I' . $row,
            $entry->bukti
                ? 'Tersedia'
                : 'Tidak tersedia'
        );

        $sheet->setCellValue(
            'J' . $row,
            $entry->status ?? 'Menunggu'
        );

        $sheet->setCellValue(
            'K' . $row,
            $entry->catatan_mentor ?? '-'
        );

        $row++;
    }

    /*
    |--------------------------------------------------------------------------
    | Border
    |--------------------------------------------------------------------------
    */

    if ($row > 2) {

        $sheet->getStyle(
            'A1:K' . ($row - 1)
        )->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Alignment
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle(
        'A2:A' . max(2, $row - 1)
    )->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    $sheet->getStyle(
        'B2:B' . max(2, $row - 1)
    )->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    $sheet->getStyle(
        'I2:J' . max(2, $row - 1)
    )->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    /*
    |--------------------------------------------------------------------------
    | Wrap Text
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle(
        'F2:H' . max(2, $row - 1)
    )->getAlignment()
        ->setWrapText(true);

    $sheet->getStyle(
        'K2:K' . max(2, $row - 1)
    )->getAlignment()
        ->setWrapText(true);

    /*
    |--------------------------------------------------------------------------
    | Auto Width
    |--------------------------------------------------------------------------
    */

    foreach (range('A', 'K') as $column) {

        $sheet->getColumnDimension($column)
            ->setAutoSize(true);
    }

    /*
    |--------------------------------------------------------------------------
    | Freeze Header
    |--------------------------------------------------------------------------
    */

    $sheet->freezePane('A2');

    /*
    |--------------------------------------------------------------------------
    | Auto Filter
    |--------------------------------------------------------------------------
    */

    if ($row > 2) {

        $sheet->setAutoFilter(
            'A1:K' . ($row - 1)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Nama File
    |--------------------------------------------------------------------------
    */

    $filename =
        'monitoring-logbook-' .
        now()->format('Y-m-d-His') .
        '.xlsx';

    /*
    |--------------------------------------------------------------------------
    | Download Excel
    |--------------------------------------------------------------------------
    */

    $writer =
        new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
            $spreadsheet
        );

    return response()->streamDownload(
        function () use ($writer) {

            $writer->save('php://output');

        },
        $filename,
        [
            'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]
    );
}
/**
 * Export Role User ke Excel
 */
public function roleUser(Request $request)
{
    $users = \App\Models\User::with('role')
        ->latest()
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Buat Spreadsheet
    |--------------------------------------------------------------------------
    */

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setTitle('Role User');

    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    $headers = [
        'No',
        'Nama',
        'Email',
        'Role',
    ];

    foreach ($headers as $index => $header) {

        $column =
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                $index + 1
            );

        $sheet->setCellValue(
            $column . '1',
            $header
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Style Header
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle('A1:D1')
        ->getFont()
        ->setBold(true);

    $sheet->getStyle('A1:D1')
        ->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    $sheet->getStyle('A1:D1')
        ->getAlignment()
        ->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        );

    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

    $row = 2;

    foreach ($users as $index => $user) {

        $sheet->setCellValue(
            'A' . $row,
            $index + 1
        );

        $sheet->setCellValue(
            'B' . $row,
            $user->name ?? '-'
        );

        $sheet->setCellValue(
            'C' . $row,
            $user->email ?? '-'
        );

        $sheet->setCellValue(
            'D' . $row,
            $user->role?->name ?? '-'
        );

        $row++;
    }

    /*
    |--------------------------------------------------------------------------
    | Border
    |--------------------------------------------------------------------------
    */

    if ($row > 2) {

        $sheet->getStyle(
            'A1:D' . ($row - 1)
        )->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Alignment
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle(
        'A2:A' . max(2, $row - 1)
    )->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    /*
    |--------------------------------------------------------------------------
    | Auto Width
    |--------------------------------------------------------------------------
    */

    foreach (range('A', 'D') as $column) {

        $sheet->getColumnDimension($column)
            ->setAutoSize(true);
    }

    /*
    |--------------------------------------------------------------------------
    | Freeze Header
    |--------------------------------------------------------------------------
    */

    $sheet->freezePane('A2');

    /*
    |--------------------------------------------------------------------------
    | Auto Filter
    |--------------------------------------------------------------------------
    */

    if ($row > 2) {

        $sheet->setAutoFilter(
            'A1:D' . ($row - 1)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Nama File
    |--------------------------------------------------------------------------
    */

    $filename =
        'role-user-' .
        now()->format('Y-m-d-His') .
        '.xlsx';

    /*
    |--------------------------------------------------------------------------
    | Download Excel
    |--------------------------------------------------------------------------
    */

    $writer =
        new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
            $spreadsheet
        );

    return response()->streamDownload(
        function () use ($writer) {

            $writer->save('php://output');

        },
        $filename,
        [
            'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]
    );
}
/**

* Export data Role Menu / Hak Akses ke Excel
  */
  public function roleMenu(Request $request)
  {
  $roleMenus = \App\Models\RoleMenu::with([
  'role',
  'menu'
  ])
  ->orderBy('role_id')
  ->orderBy('menu_id')
  ->get();

/*                                                                         |
  | -------------------------------------------------------------------------- |
  | Buat Spreadsheet                                                           |
  | -------------------------------------------------------------------------- |
  | */                                                                         

  $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

  $sheet = $spreadsheet->getActiveSheet();

  $sheet->setTitle('Role Menu');

/*                                                                         |
  | -------------------------------------------------------------------------- |
  | Header                                                                     |
  | -------------------------------------------------------------------------- |
  | */                                                                         

  $headers = [
  'No',
  'Role',
  'Menu',
  'Status',
  ];

  foreach ($headers as $index => $header) {

   $column =
       \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
           $index + 1
       );

   $sheet->setCellValue(
       $column . '1',
       $header
   );

  }
  $row = 2;

  foreach ($roleMenus as $index => $roleMenu) {

   $sheet->setCellValue(
       'A' . $row,
       $index + 1
   );

   $sheet->setCellValue(
       'B' . $row,
       $roleMenu->role?->name ?? '-'
   );

   $sheet->setCellValue(
       'C' . $row,
       $roleMenu->menu?->name ?? '-'
   );

   $sheet->setCellValue(
       'D' . $row,
       $roleMenu->status ?? 'inactive'
   );

   $row++;

  }


  $sheet->getStyle('A1:D1')
  ->getFont()
  ->setBold(true);

  $sheet->getStyle('A1:D1')
  ->getAlignment()
  ->setHorizontal(
  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
  );

  $sheet->getStyle('A1:D1')
  ->getAlignment()
  ->setVertical(
  \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
  );

  if ($row > 2) {

   $sheet->getStyle(
       'A1:D' . ($row - 1)
   )->getBorders()
       ->getAllBorders()
       ->setBorderStyle(
           \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
       );

  }


  $sheet->getStyle(
  'A2:A' . max(2, $row - 1)
  )->getAlignment()
  ->setHorizontal(
  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
  );

  $sheet->getStyle(
  'D2:D' . max(2, $row - 1)
  )->getAlignment()
  ->setHorizontal(
  \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
  );


  foreach (range('A', 'D') as $column) {

   $sheet->getColumnDimension($column)
       ->setAutoSize(true);

  }

  $sheet->freezePane('A2');


  if ($row > 2) {

   $sheet->setAutoFilter(
       'A1:D' . ($row - 1)
   );

  }


  $filename =
  'role-menu-' .
  now()->format('Y-m-d-His') .
  '.xlsx';

  $writer =
  new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
  $spreadsheet
  );

  return response()->streamDownload(
  function () use ($writer) {

       $writer->save('php://output');

   },
   $filename,
   [
       'Content-Type' =>
           'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
   ]

  );
  }
/**
 * Export Log Histori ke Excel
 */
public function history(Request $request)
{
    $logs = \App\Models\ActivityLog::with('user')
        ->orderByDesc('created_at')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Buat Spreadsheet
    |--------------------------------------------------------------------------
    */

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setTitle('Log Histori');


    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    $headers = [
        'No',
        'Waktu',
        'User',
        'Module',
        'Action',
        'Deskripsi',
        'Data Sebelum',
        'Data Sesudah',
        'IP Address',
        'User Agent',
    ];

    foreach ($headers as $index => $header) {

        $column =
            \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                $index + 1
            );

        $sheet->setCellValue(
            $column . '1',
            $header
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Style Header
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle('A1:J1')
        ->getFont()
        ->setBold(true);

    $sheet->getStyle('A1:J1')
        ->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    $sheet->getStyle('A1:J1')
        ->getAlignment()
        ->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        );


    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

    $row = 2;
    $no = 1;

    foreach ($logs as $log) {

        /*
        |--------------------------------------------------------------------------
        | Format Old Data
        |--------------------------------------------------------------------------
        */

        $oldData = '-';

        if ($log->old_data) {

            $decodedOld = json_decode(
                $log->old_data,
                true
            );

            if (
                json_last_error() === JSON_ERROR_NONE
            ) {

                $oldData = json_encode(
                    $decodedOld,
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE |
                    JSON_UNESCAPED_SLASHES
                );

            } else {

                $oldData = $log->old_data;

            }
        }


        /*
        |--------------------------------------------------------------------------
        | Format New Data
        |--------------------------------------------------------------------------
        */

        $newData = '-';

        if ($log->new_data) {

            $decodedNew = json_decode(
                $log->new_data,
                true
            );

            if (
                json_last_error() === JSON_ERROR_NONE
            ) {

                $newData = json_encode(
                    $decodedNew,
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE |
                    JSON_UNESCAPED_SLASHES
                );

            } else {

                $newData = $log->new_data;

            }
        }


        /*
        |--------------------------------------------------------------------------
        | Isi Data
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'A' . $row,
            $no
        );

        $sheet->setCellValue(
            'B' . $row,
            optional($log->created_at)
                ->format('Y-m-d H:i:s')
        );

        $sheet->setCellValue(
            'C' . $row,
            optional($log->user)->name ?? 'System'
        );

        $sheet->setCellValue(
            'D' . $row,
            $log->module
        );

        $sheet->setCellValue(
            'E' . $row,
            $log->action
        );

        $sheet->setCellValue(
            'F' . $row,
            $log->description
        );

        $sheet->setCellValue(
            'G' . $row,
            $oldData
        );

        $sheet->setCellValue(
            'H' . $row,
            $newData
        );

        $sheet->setCellValue(
            'I' . $row,
            $log->ip_address
        );

        $sheet->setCellValue(
            'J' . $row,
            $log->user_agent
        );

        $row++;
        $no++;
    }


    /*
    |--------------------------------------------------------------------------
    | Border
    |--------------------------------------------------------------------------
    */

    if ($row > 2) {

        $sheet->getStyle(
            'A1:J' . ($row - 1)
        )->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Alignment
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle(
        'A2:A' . max(2, $row - 1)
    )->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    $sheet->getStyle(
        'E2:E' . max(2, $row - 1)
    )->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );


    /*
    |--------------------------------------------------------------------------
    | Wrap Text
    |--------------------------------------------------------------------------
    */

    if ($row > 2) {

        $sheet->getStyle(
            'F2:H' . ($row - 1)
        )->getAlignment()
            ->setWrapText(true);
    }


    /*
    |--------------------------------------------------------------------------
    | Auto Width
    |--------------------------------------------------------------------------
    */

    foreach (range('A', 'J') as $column) {

        $sheet->getColumnDimension($column)
            ->setAutoSize(true);
    }


    /*
    |--------------------------------------------------------------------------
    | Lebar Khusus
    |--------------------------------------------------------------------------
    */

    $sheet->getColumnDimension('F')
        ->setWidth(35);

    $sheet->getColumnDimension('G')
        ->setWidth(50);

    $sheet->getColumnDimension('H')
        ->setWidth(50);

    $sheet->getColumnDimension('J')
        ->setWidth(45);


    /*
    |--------------------------------------------------------------------------
    | Freeze Header
    |--------------------------------------------------------------------------
    */

    $sheet->freezePane('A2');


    /*
    |--------------------------------------------------------------------------
    | Auto Filter
    |--------------------------------------------------------------------------
    */

    if ($row > 2) {

        $sheet->setAutoFilter(
            'A1:J' . ($row - 1)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Nama File
    |--------------------------------------------------------------------------
    */

    $filename =
        'log-histori-' .
        now()->format('Y-m-d-His') .
        '.xlsx';


    /*
    |--------------------------------------------------------------------------
    | Download Excel
    |--------------------------------------------------------------------------
    */

    $writer =
        new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
            $spreadsheet
        );

    return response()->streamDownload(
        function () use ($writer) {

            $writer->save('php://output');

        },
        $filename,
        [
            'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]
    );
}
/**
 * Export Monitoring Logbook Mentor ke Excel
 */
public function logbookMentor(Request $request)
{
    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Cari mentor berdasarkan user yang sedang login
    |--------------------------------------------------------------------------
    */

    $mentor = \App\Models\Mentor::where(
        'nama_mentor',
        $user->name
    )->first();

    if (!$mentor) {
        abort(404, 'Data mentor tidak ditemukan.');
    }

    /*
    |--------------------------------------------------------------------------
    | Ambil logbook peserta mentor
    |--------------------------------------------------------------------------
    */

    $query = \App\Models\Logbook::with('user')
        ->whereHas('user', function ($query) use ($mentor) {
            $query->where(
                'mentor_id',
                $mentor->id
            );
        })
        ->orderByDesc('tanggal')
        ->orderByDesc('id');

    /*
    |--------------------------------------------------------------------------
    | Filter Peserta
    |--------------------------------------------------------------------------
    |
    | Jika user_id dikirim dari halaman mentor,
    | export hanya peserta tersebut.
    |
    */

    if ($request->filled('user_id')) {
        $query->where(
            'user_id',
            $request->user_id
        );
    }

    $logbooks = $query->get();

    /*
    |--------------------------------------------------------------------------
    | Buat Spreadsheet
    |--------------------------------------------------------------------------
    */

    $spreadsheet = new Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setTitle('Monitoring Logbook');

    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    $headers = [
        'No',
        'Tanggal',
        'Nama Peserta',
        'Email',
        'Aktivitas',
        'Hasil',
        'Catatan Peserta',
        'Bukti Kegiatan',
        'Status',
        'Catatan Mentor',
    ];

    foreach ($headers as $index => $header) {

        $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
            $index + 1
        );

        $sheet->setCellValue(
            $column . '1',
            $header
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Style Header
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle('A1:J1')
        ->getFont()
        ->setBold(true);

    $sheet->getStyle('A1:J1')
        ->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    $sheet->getStyle('A1:J1')
        ->getAlignment()
        ->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        );

    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

    $row = 2;

    foreach ($logbooks as $index => $logbook) {

        $sheet->setCellValue(
            'A' . $row,
            $index + 1
        );

        $sheet->setCellValue(
            'B' . $row,
            $logbook->tanggal
                ? $logbook->tanggal->format('Y-m-d')
                : '-'
        );

        $sheet->setCellValue(
            'C' . $row,
            $logbook->user?->name ?? '-'
        );

        $sheet->setCellValue(
            'D' . $row,
            $logbook->user?->email ?? '-'
        );

        $sheet->setCellValue(
            'E' . $row,
            $logbook->aktivitas ?? '-'
        );

        $sheet->setCellValue(
            'F' . $row,
            $logbook->hasil ?? '-'
        );

        $sheet->setCellValue(
            'G' . $row,
            $logbook->catatan ?? '-'
        );

        $sheet->setCellValue(
            'H' . $row,
            $logbook->bukti
                ? 'Tersedia'
                : 'Tidak tersedia'
        );

        $sheet->setCellValue(
            'I' . $row,
            $logbook->status ?? 'Menunggu'
        );

        $sheet->setCellValue(
            'J' . $row,
            $logbook->catatan_mentor ?? '-'
        );

        $row++;
    }

    /*
    |--------------------------------------------------------------------------
    | Border
    |--------------------------------------------------------------------------
    */

    if ($row > 2) {

        $sheet->getStyle(
            'A1:J' . ($row - 1)
        )->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Alignment
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle(
        'A2:A' . max(2, $row - 1)
    )->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    $sheet->getStyle(
        'B2:B' . max(2, $row - 1)
    )->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    $sheet->getStyle(
        'H2:I' . max(2, $row - 1)
    )->getAlignment()
        ->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

    /*
    |--------------------------------------------------------------------------
    | Wrap Text
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle(
        'E2:G' . max(2, $row - 1)
    )->getAlignment()
        ->setWrapText(true);

    $sheet->getStyle(
        'J2:J' . max(2, $row - 1)
    )->getAlignment()
        ->setWrapText(true);

    /*
    |--------------------------------------------------------------------------
    | Auto Width
    |--------------------------------------------------------------------------
    */

    foreach (range('A', 'J') as $column) {

        $sheet->getColumnDimension($column)
            ->setAutoSize(true);
    }

    /*
    |--------------------------------------------------------------------------
    | Lebarkan kolom teks
    |--------------------------------------------------------------------------
    */

    $sheet->getColumnDimension('E')
        ->setWidth(35);

    $sheet->getColumnDimension('F')
        ->setWidth(35);

    $sheet->getColumnDimension('G')
        ->setWidth(30);

    $sheet->getColumnDimension('J')
        ->setWidth(30);

    /*
    |--------------------------------------------------------------------------
    | Freeze Header
    |--------------------------------------------------------------------------
    */

    $sheet->freezePane('A2');

    /*
    |--------------------------------------------------------------------------
    | Auto Filter
    |--------------------------------------------------------------------------
    */

    $sheet->setAutoFilter(
        'A1:J' . max(1, $row - 1)
    );

    /*
    |--------------------------------------------------------------------------
    | Nama File
    |--------------------------------------------------------------------------
    */

    if ($request->filled('user_id')) {

        $peserta = \App\Models\User::find(
            $request->user_id
        );

        $namaPeserta = $peserta
            ? preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                strtolower($peserta->name)
            )
            : 'peserta';

        $filename =
            'logbook-' .
            $namaPeserta .
            '-' .
            now()->format('Y-m-d-His') .
            '.xlsx';

    } else {

        $filename =
            'monitoring-logbook-mentor-' .
            now()->format('Y-m-d-His') .
            '.xlsx';
    }

    /*
    |--------------------------------------------------------------------------
    | Download Excel
    |--------------------------------------------------------------------------
    */

    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(
        function () use ($writer) {
            $writer->save('php://output');
        },
        $filename,
        [
            'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]
    );
}
public function logbookPeserta(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Ambil user yang sedang login
    |--------------------------------------------------------------------------
    */

    $user = auth()->user();

    if (!$user) {
        abort(401, 'Anda harus login terlebih dahulu.');
    }

    /*
    |--------------------------------------------------------------------------
    | Ambil logbook milik peserta yang sedang login
    |--------------------------------------------------------------------------
    */

    $logbooks = Logbook::where(
        'user_id',
        $user->id
    )
        ->orderByDesc('tanggal')
        ->orderByDesc('id')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Buat Spreadsheet
    |--------------------------------------------------------------------------
    */

    $spreadsheet = new Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setTitle('Logbook Saya');

    /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

    $headers = [
        'No',
        'Tanggal',
        'Aktivitas',
        'Hasil',
        'Catatan',
        'Bukti Kegiatan',
        'Status',
        'Catatan Mentor',
    ];

    foreach ($headers as $index => $header) {

        $column = Coordinate::stringFromColumnIndex(
            $index + 1
        );

        $sheet->setCellValue(
            $column . '1',
            $header
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Style Header
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle('A1:H1')
        ->getFont()
        ->setBold(true);

    $sheet->getStyle('A1:H1')
        ->getAlignment()
        ->setHorizontal(
            Alignment::HORIZONTAL_CENTER
        );

    $sheet->getStyle('A1:H1')
        ->getAlignment()
        ->setVertical(
            Alignment::VERTICAL_CENTER
        );

    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

    $row = 2;

    foreach ($logbooks as $index => $logbook) {

        $sheet->setCellValue(
            'A' . $row,
            $index + 1
        );

        $sheet->setCellValue(
            'B' . $row,
            $logbook->tanggal
                ? $logbook->tanggal->format('Y-m-d')
                : '-'
        );

        $sheet->setCellValue(
            'C' . $row,
            $logbook->aktivitas ?? '-'
        );

        $sheet->setCellValue(
            'D' . $row,
            $logbook->hasil ?? '-'
        );

        $sheet->setCellValue(
            'E' . $row,
            $logbook->catatan ?? '-'
        );

        $sheet->setCellValue(
            'F' . $row,
            $logbook->bukti
                ? 'Tersedia'
                : 'Tidak tersedia'
        );

        $sheet->setCellValue(
            'G' . $row,
            $logbook->status ?? 'Menunggu'
        );

        $sheet->setCellValue(
            'H' . $row,
            $logbook->catatan_mentor ?? '-'
        );

        $row++;
    }

    /*
    |--------------------------------------------------------------------------
    | Last Row
    |--------------------------------------------------------------------------
    */

    $lastRow = max(
        1,
        $row - 1
    );

    /*
    |--------------------------------------------------------------------------
    | Border
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle(
        'A1:H' . $lastRow
    )
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(
            Border::BORDER_THIN
        );

    /*
    |--------------------------------------------------------------------------
    | Alignment
    |--------------------------------------------------------------------------
    */

    if ($lastRow >= 2) {

        $sheet->getStyle(
            'A2:A' . $lastRow
        )
            ->getAlignment()
            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle(
            'B2:B' . $lastRow
        )
            ->getAlignment()
            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle(
            'F2:G' . $lastRow
        )
            ->getAlignment()
            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Wrap Text
    |--------------------------------------------------------------------------
    */

    if ($lastRow >= 2) {

        $sheet->getStyle(
            'C2:E' . $lastRow
        )
            ->getAlignment()
            ->setWrapText(true);

        $sheet->getStyle(
            'H2:H' . $lastRow
        )
            ->getAlignment()
            ->setWrapText(true);
    }

    /*
    |--------------------------------------------------------------------------
    | Auto Width
    |--------------------------------------------------------------------------
    */

    foreach (range('A', 'H') as $column) {

        $sheet->getColumnDimension($column)
            ->setAutoSize(true);
    }

    /*
    |--------------------------------------------------------------------------
    | Lebarkan Kolom Teks
    |--------------------------------------------------------------------------
    */

    $sheet->getColumnDimension('C')
        ->setWidth(35);

    $sheet->getColumnDimension('D')
        ->setWidth(35);

    $sheet->getColumnDimension('E')
        ->setWidth(30);

    $sheet->getColumnDimension('H')
        ->setWidth(30);

    /*
    |--------------------------------------------------------------------------
    | Freeze Header
    |--------------------------------------------------------------------------
    */

    $sheet->freezePane('A2');

    /*
    |--------------------------------------------------------------------------
    | Auto Filter
    |--------------------------------------------------------------------------
    */

    $sheet->setAutoFilter(
        'A1:H' . $lastRow
    );

    /*
    |--------------------------------------------------------------------------
    | Nama File
    |--------------------------------------------------------------------------
    */

    $namaPeserta = preg_replace(
        '/[^A-Za-z0-9\-]/',
        '-',
        strtolower($user->name)
    );

    $filename =
        'logbook-saya-' .
        $namaPeserta .
        '-' .
        now()->format('Y-m-d-His') .
        '.xlsx';

    /*
    |--------------------------------------------------------------------------
    | Download Excel
    |--------------------------------------------------------------------------
    */

    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(
        function () use ($writer) {

            $writer->save(
                'php://output'
            );
        },
        $filename,
        [
            'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]
    );
}

/**
 * Export Detail Arsip Pengajuan ke Excel
 */
public function arsipPengajuan($id)
{
    /*
    |--------------------------------------------------------------------------
    | AMBIL PENGAJUAN
    |--------------------------------------------------------------------------
    */

    $pengajuan = PengajuanMagang::with([
        'mentor',
        'anggota',
    ])
        ->whereNotNull('archived_at')
        ->findOrFail($id);


    /*
    |--------------------------------------------------------------------------
    | AMBIL ANGGOTA
    |--------------------------------------------------------------------------
    */

    $anggota = \App\Models\AnggotaMagang::query()
        ->where(
            'pengajuan_magang_id',
            $pengajuan->id
        )
        ->get();


    /*
    |--------------------------------------------------------------------------
    | KUMPULKAN EMAIL PESERTA
    |--------------------------------------------------------------------------
    */

    $emails = collect();

    if ($pengajuan->email_ketua) {

        $emails->push(
            strtolower(
                trim($pengajuan->email_ketua)
            )
        );

    }


    foreach ($anggota as $item) {

        if ($item->email) {

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


    /*
    |--------------------------------------------------------------------------
    | AMBIL USER
    |--------------------------------------------------------------------------
    */

    $users = \App\Models\User::query()
        ->where(function ($query) use ($emails) {

            foreach ($emails as $email) {

                $query->orWhereRaw(
                    'LOWER(email) = ?',
                    [$email]
                );

            }

        })
        ->with('mentor')
        ->get();


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
    */

    $emailKetua = strtolower(
        trim($pengajuan->email_ketua ?? '')
    );


    $userKetua =
        $usersByEmail->get(
            $emailKetua
        );


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

        'peran' =>
            'Ketua',

        'mentor' =>
            optional($pengajuan->mentor)->nama_mentor
            ?: optional(
                optional($userKetua)->mentor
            )->nama_mentor
            ?: '-',

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


        if (
            $emailAnggota !== '' &&
            $emailAnggota === $emailKetua
        ) {

            continue;

        }


        $user =
            $usersByEmail->get(
                $emailAnggota
            );


        $peserta->push([

            'id' =>
                optional($user)->id,

            'nama' =>
                $item->nama_anggota
                ?: optional($user)->name
                ?: '-',

            'email' =>
                $item->email
                ?: optional($user)->email
                ?: '-',

            'no_hp' =>
                $item->no_hp
                ?: '-',

            'peran' =>
                'Anggota',

            'mentor' =>
                optional($pengajuan->mentor)->nama_mentor
                ?: optional(
                    optional($user)->mentor
                )->nama_mentor
                ?: '-',

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
    */

    $logbooks = \App\Models\Logbook::query()
        ->with('user')
        ->where(function ($query) use (
            $pengajuan,
            $userIds
        ) {

            /*
            |------------------------------------------------------------------
            | Logbook yang terhubung langsung dengan pengajuan
            |------------------------------------------------------------------
            */

            $query->where(
                'pengajuan_magang_id',
                $pengajuan->id
            );


            /*
            |------------------------------------------------------------------
            | Logbook peserta yang belum punya
            | pengajuan_magang_id
            |------------------------------------------------------------------
            */

            if ($userIds->isNotEmpty()) {

                $query->orWhere(function ($subQuery) use ($userIds) {

                    $subQuery
                        ->whereNull('pengajuan_magang_id')
                        ->whereIn(
                            'user_id',
                            $userIds
                        );

                });

            }

        })
        ->orderBy('tanggal', 'asc')
        ->orderBy('id', 'asc')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | BUAT SPREADSHEET
    |--------------------------------------------------------------------------
    */

    $spreadsheet =
        new Spreadsheet();


    /*
    |--------------------------------------------------------------------------
    | SHEET 1 - DATA PENGAJUAN
    |--------------------------------------------------------------------------
    */

    $sheet =
        $spreadsheet->getActiveSheet();


    $sheet->setTitle(
        'Data Pengajuan'
    );


    $dataPengajuan = [

        ['Data Pengajuan', ''],

        ['Kode Pengajuan', $pengajuan->kode_pengajuan],

        ['Nama Ketua', $pengajuan->nama_ketua],

        ['Email Ketua', $pengajuan->email_ketua],

        ['No. HP Ketua', $pengajuan->no_hp],

        ['Perguruan Tinggi', $pengajuan->universitas],

        ['Semester', $pengajuan->semester],

        ['Tanggal Mulai', $pengajuan->tanggal_mulai],

        ['Tanggal Selesai', $pengajuan->tanggal_selesai],

        ['Status', $pengajuan->status],

        [
            'Mentor',
            optional($pengajuan->mentor)->nama_mentor
                ?: '-'
        ],

        ['Diarsipkan', $pengajuan->archived_at],

        ['Catatan', $pengajuan->catatan ?: '-'],

    ];


    foreach ($dataPengajuan as $rowIndex => $rowData) {

        $excelRow =
            $rowIndex + 1;


        $sheet->setCellValue(
            'A' . $excelRow,
            $rowData[0]
        );


        $sheet->setCellValue(
            'B' . $excelRow,
            $rowData[1]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | STYLE DATA PENGAJUAN
    |--------------------------------------------------------------------------
    */

    $sheet->getStyle('A1:B1')
        ->getFont()
        ->setBold(true);


    $sheet->getStyle('A1:B13')
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(
            Border::BORDER_THIN
        );


    $sheet->getStyle('A1:A13')
        ->getFont()
        ->setBold(true);


    $sheet->getStyle('A1:B13')
        ->getAlignment()
        ->setVertical(
            Alignment::VERTICAL_TOP
        );


    $sheet->getStyle('B13')
        ->getAlignment()
        ->setWrapText(true);


    $sheet->getColumnDimension('A')
        ->setWidth(25);


    $sheet->getColumnDimension('B')
        ->setWidth(50);


    /*
    |--------------------------------------------------------------------------
    | SHEET 2 - PESERTA
    |--------------------------------------------------------------------------
    */

    $pesertaSheet =
        $spreadsheet->createSheet();


    $pesertaSheet->setTitle(
        'Data Peserta'
    );


    $headersPeserta = [

        'No',

        'Nama Peserta',

        'Email',

        'No. HP',

        'Peran',

        'Mentor',

    ];


    foreach (
        $headersPeserta
        as $index => $header
    ) {

        $column =
            Coordinate::stringFromColumnIndex(
                $index + 1
            );


        $pesertaSheet->setCellValue(
            $column . '1',
            $header
        );

    }


    $pesertaSheet
        ->getStyle('A1:F1')
        ->getFont()
        ->setBold(true);


    $row = 2;


    foreach (
        $peserta as $index => $item
    ) {

        $pesertaSheet->setCellValue(
            'A' . $row,
            $index + 1
        );

        $pesertaSheet->setCellValue(
            'B' . $row,
            $item['nama']
        );

        $pesertaSheet->setCellValue(
            'C' . $row,
            $item['email']
        );

        $pesertaSheet->setCellValue(
            'D' . $row,
            $item['no_hp']
        );

        $pesertaSheet->setCellValue(
            'E' . $row,
            $item['peran']
        );

        $pesertaSheet->setCellValue(
            'F' . $row,
            $item['mentor']
        );

        $row++;

    }


    $pesertaLastRow =
        max(
            1,
            $row - 1
        );


    $pesertaSheet
        ->getStyle(
            'A1:F' . $pesertaLastRow
        )
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(
            Border::BORDER_THIN
        );


    foreach (
        range('A', 'F')
        as $column
    ) {

        $pesertaSheet
            ->getColumnDimension($column)
            ->setAutoSize(true);

    }


    $pesertaSheet->freezePane('A2');


    if ($pesertaLastRow >= 2) {

        $pesertaSheet->setAutoFilter(
            'A1:F' . $pesertaLastRow
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SHEET 3 - LOGBOOK
    |--------------------------------------------------------------------------
    */

    $logbookSheet =
        $spreadsheet->createSheet();


    $logbookSheet->setTitle(
        'Riwayat Logbook'
    );


    $headersLogbook = [

        'No',

        'Tanggal',

        'Peserta',

        'Email',

        'Aktivitas',

        'Hasil',

        'Catatan Peserta',

        'Catatan Mentor',

        'Status',

        'Bukti',

    ];


    foreach (
        $headersLogbook
        as $index => $header
    ) {

        $column =
            Coordinate::stringFromColumnIndex(
                $index + 1
            );


        $logbookSheet->setCellValue(
            $column . '1',
            $header
        );

    }


    $logbookSheet
        ->getStyle('A1:J1')
        ->getFont()
        ->setBold(true);


    $row = 2;


    foreach (
        $logbooks as $index => $logbook
    ) {

        $user =
            $logbook->user;


        $logbookSheet->setCellValue(
            'A' . $row,
            $index + 1
        );


        $logbookSheet->setCellValue(
            'B' . $row,
            $logbook->tanggal
        );


        $logbookSheet->setCellValue(
            'C' . $row,
            $user?->name
                ?? '-'
        );


        $logbookSheet->setCellValue(
            'D' . $row,
            $user?->email
                ?? '-'
        );


        $logbookSheet->setCellValue(
            'E' . $row,
            $logbook->aktivitas
                ?? '-'
        );


        $logbookSheet->setCellValue(
            'F' . $row,
            $logbook->hasil
                ?? '-'
        );


        $logbookSheet->setCellValue(
            'G' . $row,
            $logbook->catatan
                ?? '-'
        );


        $logbookSheet->setCellValue(
            'H' . $row,
            $logbook->catatan_mentor
                ?? '-'
        );


        $logbookSheet->setCellValue(
            'I' . $row,
            $logbook->status
                ?? '-'
        );


        $logbookSheet->setCellValue(
            'J' . $row,
            $logbook->bukti
                ?? '-'
        );


        $row++;

    }


    $logbookLastRow =
        max(
            1,
            $row - 1
        );


    $logbookSheet
        ->getStyle(
            'A1:J' . $logbookLastRow
        )
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(
            Border::BORDER_THIN
        );


    $logbookSheet
        ->getStyle(
            'A1:J' . $logbookLastRow
        )
        ->getAlignment()
        ->setVertical(
            Alignment::VERTICAL_TOP
        );


    foreach (
        range('A', 'J')
        as $column
    ) {

        $logbookSheet
            ->getColumnDimension($column)
            ->setAutoSize(true);

    }


    $logbookSheet
        ->getStyle(
            'E2:H' . $logbookLastRow
        )
        ->getAlignment()
        ->setWrapText(true);


    $logbookSheet->freezePane('A2');


    if ($logbookLastRow >= 2) {

        $logbookSheet->setAutoFilter(
            'A1:J' . $logbookLastRow
        );

    }


    /*
    |--------------------------------------------------------------------------
    | NAMA FILE
    |--------------------------------------------------------------------------
    */

    $kode =
        preg_replace(
            '/[^A-Za-z0-9_-]/',
            '-',
            $pengajuan->kode_pengajuan
        );


    $filename =
        'arsip-' .
        $kode .
        '-' .
        now()->format('Y-m-d-His') .
        '.xlsx';


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */

    $writer =
        new Xlsx(
            $spreadsheet
        );


    return response()->streamDownload(
        function () use ($writer) {

            $writer->save(
                'php://output'
            );

        },
        $filename,
        [

            'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

        ]
    );
}

}