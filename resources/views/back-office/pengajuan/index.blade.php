@extends('layouts.back-office')

@section('title', 'Data Pengajuan')

@section('content')

{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Data Pengajuan
        </h3>

        <small class="text-muted">
            Kelola pengajuan peserta, keputusan penerimaan, penolakan, dan pengarsipan.
        </small>

    </div>


    {{-- ===================================================== --}}
    {{-- TOMBOL EXPORT --}}
    {{-- ===================================================== --}}

    <div>

        <button
            type="button"
            class="btn btn-success"
            data-bs-toggle="modal"
            data-bs-target="#modalExportPengajuan"
        >

            <i class="bi bi-file-earmark-excel me-2"></i>

            Export Excel

        </button>

    </div>

</div>


{{-- ========================================================= --}}
{{-- TABLE DATA PENGAJUAN --}}
{{-- ========================================================= --}}

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-3 p-md-4">

        <div class="table-responsive">

            <table
                id="tablePengajuan"
                class="table table-bordered table-hover align-middle w-100"
            >

                <thead class="table-light">

                    <tr>

                        <th
                            width="5%"
                            class="text-center text-nowrap"
                        >
                            No
                        </th>

                        <th class="text-center text-nowrap">
                            Kode Pengajuan
                        </th>

                        <th class="text-nowrap">
                            Nama Ketua
                        </th>

                        <th class="text-nowrap">
                            Email
                        </th>

                        <th class="text-nowrap">
                            Nomor HP
                        </th>

                        <th class="text-nowrap">
                            Perguruan Tinggi
                        </th>

                        <th class="text-center text-nowrap">
                            Semester
                        </th>

                        <th class="text-center text-nowrap">
                            Status
                        </th>

                        <th class="text-center text-nowrap">
                            Periode
                        </th>

                        <th class="text-nowrap">
                            Anggota
                        </th>

                        <th class="text-nowrap">
                            Catatan
                        </th>

                        <th class="text-center text-nowrap">
                            Proposal
                        </th>

                        <th class="text-center text-nowrap">
                            Surat Permohonan
                        </th>

                        <th class="text-center text-nowrap">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody id="pengajuanTableBody">

                    <tr>

                        <td
                            colspan="15"
                            class="text-center text-muted py-5"
                        >

                            Memuat data...

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL EXPORT EXCEL --}}
{{-- ========================================================= --}}

<div
    class="modal fade"
    id="modalExportPengajuan"
    tabindex="-1"
    aria-labelledby="modalExportPengajuanLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow rounded-4">


            {{-- ================================================= --}}
            {{-- MODAL HEADER --}}
            {{-- ================================================= --}}

            <div class="modal-header">

                <div>

                    <h5
                        class="modal-title fw-bold"
                        id="modalExportPengajuanLabel"
                    >

                        <i class="bi bi-file-earmark-excel text-success me-2"></i>

                        Export Data Pengajuan

                    </h5>

                    <small class="text-muted">
                        Pilih filter data yang ingin diekspor.
                    </small>

                </div>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            {{-- ================================================= --}}
            {{-- MODAL BODY --}}
            {{-- ================================================= --}}

            <div class="modal-body">

                <form id="formExportPengajuan">


                    {{-- ================================================= --}}
                    {{-- STATUS --}}
                    {{-- ================================================= --}}

                    <div class="mb-3">

                        <label
                            for="export_status"
                            class="form-label fw-semibold"
                        >
                            Status Pengajuan
                        </label>

                        <select
                            id="export_status"
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                Semua Status
                            </option>

                            <option value="Pending">
                                Menunggu
                            </option>

                            <option value="Diterima">
                                Diterima
                            </option>

                            <option value="Ditolak">
                                Ditolak
                            </option>

                        </select>

                    </div>


                    {{-- ================================================= --}}
                    {{-- PERGURUAN TINGGI --}}
                    {{-- ================================================= --}}

                    <div class="mb-3">

                        <label
                            for="export_universitas"
                            class="form-label fw-semibold"
                        >
                            Perguruan Tinggi
                        </label>

                        <input
                            type="text"
                            id="export_universitas"
                            name="universitas"
                            class="form-control"
                            placeholder="Contoh: Universitas Airlangga"
                        >

                        <small class="text-muted">
                            Kosongkan jika ingin mengambil semua perguruan tinggi.
                        </small>

                    </div>


                    {{-- ================================================= --}}
                    {{-- TANGGAL --}}
                    {{-- ================================================= --}}

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label
                                for="export_tanggal_mulai_dari"
                                class="form-label fw-semibold"
                            >
                                Tanggal Mulai Dari
                            </label>

                            <input
                                type="date"
                                id="export_tanggal_mulai_dari"
                                name="tanggal_mulai_dari"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-6">

                            <label
                                for="export_tanggal_mulai_sampai"
                                class="form-label fw-semibold"
                            >
                                Tanggal Mulai Sampai
                            </label>

                            <input
                                type="date"
                                id="export_tanggal_mulai_sampai"
                                name="tanggal_mulai_sampai"
                                class="form-control"
                            >

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- INFO --}}
                    {{-- ================================================= --}}

                    <div class="alert alert-info mt-4 mb-0">

                        <div class="d-flex">

                            <i class="bi bi-info-circle-fill me-2 mt-1"></i>

                            <div>

                                <strong>
                                    Informasi Export
                                </strong>

                                <div class="small mt-1">

                                    Jika semua filter dikosongkan,
                                    seluruh data pengajuan akan diekspor.

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>


            {{-- ================================================= --}}
            {{-- MODAL FOOTER --}}
            {{-- ================================================= --}}

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light"
                    id="btnResetExport"
                >

                    <i class="bi bi-arrow-counterclockwise me-1"></i>

                    Reset

                </button>


                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >

                    Batal

                </button>


                <button
                    type="button"
                    class="btn btn-success"
                    id="btnExportPengajuan"
                >

                    <i class="bi bi-file-earmark-excel me-1"></i>

                    Export Excel

                </button>

            </div>

        </div>

    </div>

</div>

@endsection


@push('js')

<script>

/*
|--------------------------------------------------------------------------
| HELPER
|--------------------------------------------------------------------------
| Format tanggal disamakan dengan halaman Arsip Pengajuan.
|
| Contoh:
| 2026-08-14
| menjadi:
| 14 Agustus 2026
|--------------------------------------------------------------------------
*/

function escapeHtml(value)
{
    if (
        value === null ||
        value === undefined ||
        value === ''
    ) {

        return '-';

    }

    return $('<div>')
        .text(String(value))
        .html();
}


/*
|--------------------------------------------------------------------------
| FORMAT TANGGAL
|--------------------------------------------------------------------------
*/

function formatTanggal(value)
{
    if (!value) {

        return '-';

    }


    const date = new Date(value);


    if (isNaN(date.getTime())) {

        return escapeHtml(value);

    }


    return date.toLocaleDateString(
        'id-ID',
        {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        }
    );
}


/*
|--------------------------------------------------------------------------
| FORMAT TANGGAL + JAM
|--------------------------------------------------------------------------
| Disiapkan agar konsisten dengan halaman Arsip Pengajuan
| apabila nantinya ada kolom tanggal dan jam.
|--------------------------------------------------------------------------
*/

function formatTanggalJam(value)
{
    if (!value) {

        return '-';

    }


    const date = new Date(value);


    if (isNaN(date.getTime())) {

        return escapeHtml(value);

    }


    return date.toLocaleString(
        'id-ID',
        {
            dateStyle: 'medium',
            timeStyle: 'short'
        }
    );
}


/*
|--------------------------------------------------------------------------
| STATUS BADGE
|--------------------------------------------------------------------------
*/

function renderStatusBadge(status)
{
    const normalized =
        String(status || '')
            .toLowerCase();


    if (
        normalized === 'diterima' ||
        normalized === 'accepted'
    ) {

        return `

            <span class="badge bg-success">

                <i class="bi bi-check-circle me-1"></i>

                Diterima

            </span>

        `;

    }


    if (
        normalized === 'ditolak' ||
        normalized === 'rejected'
    ) {

        return `

            <span class="badge bg-danger">

                <i class="bi bi-x-circle me-1"></i>

                Ditolak

            </span>

        `;

    }


    return `

        <span class="badge bg-warning text-dark">

            <i class="bi bi-clock me-1"></i>

            Menunggu

        </span>

    `;
}


let tablePengajuan;


/*
|--------------------------------------------------------------------------
| DATATABLE
|--------------------------------------------------------------------------
*/

$(function () {

    tablePengajuan =
        $('#tablePengajuan').DataTable({

            destroy: true,

            processing: true,

            serverSide: false,

            autoWidth: false,

            responsive: false,

            scrollX: true,

            scrollCollapse: true,

            pageLength: 10,

            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],


            /*
            |--------------------------------------------------------------------------
            | AJAX
            |--------------------------------------------------------------------------
            */

            ajax: {

                url:
                    '/api/back-office/pengajuan',

                dataSrc: function (response) {

                    return response.data || [];

                },

                error: function (xhr) {

                    console.error(
                        'DataTables AJAX Error:',
                        xhr
                    );


                    const message =
                        xhr.responseJSON?.message ||
                        'Gagal memuat data pengajuan.';


                    Swal.fire({

                        icon: 'error',

                        title: 'Gagal Memuat Data',

                        text: message,

                        confirmButtonText: 'OK'

                    });

                }

            },


            /*
            |--------------------------------------------------------------------------
            | COLUMNS
            |--------------------------------------------------------------------------
            */

            columns: [


                /*
                |--------------------------------------------------------------------------
                | NO
                |--------------------------------------------------------------------------
                */

                {

                    data: null,

                    className:
                        'text-center text-nowrap',

                    render: function (
                        data,
                        type,
                        row,
                        meta
                    ) {

                        return meta.row + 1;

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | KODE PENGAJUAN
                |--------------------------------------------------------------------------
                */

                {

                    data: 'kode_pengajuan',

                    className:
                        'text-center text-nowrap',

                    render: function (data) {

                        return `

                            <span class="fw-semibold">

                                ${escapeHtml(data)}

                            </span>

                        `;

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | NAMA KETUA
                |--------------------------------------------------------------------------
                */

                {

                    data: 'nama_ketua',

                    render: function (data) {

                        return escapeHtml(data);

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | EMAIL
                |--------------------------------------------------------------------------
                */

                {

                    data: 'email_ketua',

                    render: function (data) {

                        return escapeHtml(data);

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | NOMOR HP
                |--------------------------------------------------------------------------
                */

                {

                    data: 'no_hp',

                    render: function (data) {

                        return escapeHtml(data);

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | PERGURUAN TINGGI
                |--------------------------------------------------------------------------
                */

                {

                    data: 'universitas',

                    render: function (data) {

                        return escapeHtml(data);

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | SEMESTER
                |--------------------------------------------------------------------------
                */

                {

                    data: 'semester',

                    className:
                        'text-center text-nowrap',

                    render: function (data) {

                        return escapeHtml(data);

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                {

                    data: 'status',

                    className:
                        'text-center text-nowrap',

                    render: function (data) {

                        return renderStatusBadge(data);

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | PERIODE
                |--------------------------------------------------------------------------
                |
                | PENTING:
                | Tanggal sekarang menggunakan formatTanggal()
                | agar sama dengan halaman Arsip Pengajuan.
                |
                | Contoh:
                | 14 Agustus 2026 - 14 September 2026
                |
                */

                {

                    data: null,

                    className:
                        'text-center text-nowrap',

                    render: function (data) {

                        const mulai =
                            formatTanggal(
                                data.tanggal_mulai
                            );


                        const selesai =
                            formatTanggal(
                                data.tanggal_selesai
                            );


                        return `

                            <span class="text-nowrap">

                                ${mulai}

                                -

                                ${selesai}

                            </span>

                        `;

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | ANGGOTA
                |--------------------------------------------------------------------------
                */

                {

                    data: 'anggota',

                    render: function (data) {

                        if (
                            !Array.isArray(data) ||
                            data.length === 0
                        ) {

                            return '-';

                        }


                        return data
                            .map(function (anggota) {

                                return escapeHtml(
                                    anggota.nama_anggota
                                );

                            })
                            .join(', ');

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | CATATAN
                |--------------------------------------------------------------------------
                */

                {

                    data: 'catatan',

                    render: function (data) {

                        return escapeHtml(data);

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | PROPOSAL
                |--------------------------------------------------------------------------
                */

                {

                    data: 'proposal',

                    orderable: false,

                    searchable: false,

                    className:
                        'text-center text-nowrap',

                    render: function (
                        data,
                        type,
                        row
                    ) {

                        if (!data) {

                            return `

                                <span class="text-muted small">

                                    <i
                                        class="bi bi-file-earmark-x me-1"
                                    ></i>

                                    Tidak tersedia

                                </span>

                            `;

                        }


                        const url =
                            `/back-office/pengajuan/${row.id}/file/proposal`;


                        return `

                            <a
                                href="${escapeHtml(url)}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-sm btn-outline-primary"
                                title="Lihat Proposal"
                            >

                                <i
                                    class="bi bi-eye me-1"
                                ></i>

                                Lihat

                            </a>

                        `;

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | SURAT PERMOHONAN
                |--------------------------------------------------------------------------
                */

                {

                    data: 'surat_permohonan',

                    orderable: false,

                    searchable: false,

                    className:
                        'text-center text-nowrap',

                    render: function (
                        data,
                        type,
                        row
                    ) {

                        if (!data) {

                            return `

                                <span class="text-muted small">

                                    <i
                                        class="bi bi-file-earmark-x me-1"
                                    ></i>

                                    Tidak tersedia

                                </span>

                            `;

                        }


                        const url =
                            `/back-office/pengajuan/${row.id}/file/surat-permohonan`;


                        return `

                            <a
                                href="${escapeHtml(url)}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-sm btn-outline-primary"
                                title="Lihat Surat Permohonan"
                            >

                                <i
                                    class="bi bi-eye me-1"
                                ></i>

                                Lihat

                            </a>

                        `;

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | AKSI
                |--------------------------------------------------------------------------
                */

                {

                    data: null,

                    orderable: false,

                    searchable: false,

                    className:
                        'text-center',

                    render: function (data) {

                        const status =
                            String(
                                data.status || ''
                            ).toLowerCase();


                        const sudahDiterima =
                            status === 'diterima' ||
                            status === 'accepted';


                        const sudahDitolak =
                            status === 'ditolak' ||
                            status === 'rejected';


                        const sudahDiputus =
                            sudahDiterima ||
                            sudahDitolak;


                        /*
                        |--------------------------------------------------------------------------
                        | SUDAH DIARSIPKAN
                        |--------------------------------------------------------------------------
                        */

                        if (data.archived_at) {

                            return `

                                <div
                                    class="d-flex flex-column gap-1"
                                    style="min-width: 150px;"
                                >

                                    <button
                                        type="button"
                                        class="btn btn-secondary btn-sm"
                                        disabled
                                    >

                                        <i
                                            class="bi bi-archive-fill me-1"
                                        ></i>

                                        Sudah Diarsipkan

                                    </button>

                                </div>

                            `;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | SUDAH MEMILIKI KEPUTUSAN
                        |--------------------------------------------------------------------------
                        */

                        if (sudahDiputus) {

                            return `

                                <div
                                    class="d-flex flex-column gap-1"
                                    style="min-width: 150px;"
                                >

                                    <button
                                        type="button"
                                        class="btn btn-success btn-sm"
                                        disabled
                                    >

                                        <i
                                            class="bi bi-check-circle me-1"
                                        ></i>

                                        Terima

                                    </button>


                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm"
                                        disabled
                                    >

                                        <i
                                            class="bi bi-x-circle me-1"
                                        ></i>

                                        Tolak

                                    </button>


                                    <button
                                        type="button"
                                        class="btn btn-primary btn-sm"
                                        onclick="archivePengajuan(${data.id})"
                                    >

                                        <i
                                            class="bi bi-archive me-1"
                                        ></i>

                                        Arsipkan

                                    </button>

                                </div>

                            `;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | BELUM ADA KEPUTUSAN
                        |--------------------------------------------------------------------------
                        */

                        return `

                            <div
                                class="d-flex flex-column gap-1"
                                style="min-width: 150px;"
                            >

                                <button
                                    type="button"
                                    class="btn btn-success btn-sm"
                                    onclick="updatePengajuanStatus(
                                        ${data.id},
                                        'Diterima'
                                    )"
                                >

                                    <i
                                        class="bi bi-check-circle me-1"
                                    ></i>

                                    Terima

                                </button>


                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="updatePengajuanStatus(
                                        ${data.id},
                                        'Ditolak'
                                    )"
                                >

                                    <i
                                        class="bi bi-x-circle me-1"
                                    ></i>

                                    Tolak

                                </button>

                            </div>

                        `;

                    }

                }

            ],


            /*
            |--------------------------------------------------------------------------
            | BAHASA DATATABLE
            |--------------------------------------------------------------------------
            */

            language: {

                emptyTable:
                    'Belum ada data pengajuan.',

                processing:
                    'Memuat data...',

                search:
                    'Cari:',

                lengthMenu:
                    'Tampilkan _MENU_ data',

                info:
                    'Menampilkan _START_ sampai _END_ dari _TOTAL_ pengajuan',

                infoEmpty:
                    'Tidak ada data',

                zeroRecords:
                    'Pengajuan tidak ditemukan.',

                paginate: {

                    first:
                        'Pertama',

                    last:
                        'Terakhir',

                    next:
                        '›',

                    previous:
                        '‹'

                }

            }

        });


    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */

    $('#btnExportPengajuan').on(
        'click',
        function () {

            const status =
                $('#export_status').val();


            const universitas =
                $('#export_universitas')
                    .val()
                    .trim();


            const tanggalMulaiDari =
                $('#export_tanggal_mulai_dari')
                    .val();


            const tanggalMulaiSampai =
                $('#export_tanggal_mulai_sampai')
                    .val();


            /*
            |--------------------------------------------------------------------------
            | VALIDASI TANGGAL
            |--------------------------------------------------------------------------
            */

            if (
                tanggalMulaiDari &&
                tanggalMulaiSampai &&
                tanggalMulaiDari > tanggalMulaiSampai
            ) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Tanggal Tidak Valid',

                    text:
                        'Tanggal mulai dari tidak boleh lebih besar dari tanggal mulai sampai.',

                    confirmButtonText:
                        'OK'

                });

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | BUAT URL EXPORT
            |--------------------------------------------------------------------------
            */

            const params =
                new URLSearchParams();


            if (status) {

                params.set(
                    'status',
                    status
                );

            }


            if (universitas) {

                params.set(
                    'universitas',
                    universitas
                );

            }


            if (tanggalMulaiDari) {

                params.set(
                    'tanggal_mulai_dari',
                    tanggalMulaiDari
                );

            }


            if (tanggalMulaiSampai) {

                params.set(
                    'tanggal_mulai_sampai',
                    tanggalMulaiSampai
                );

            }


            const baseUrl =
                `{{ route('back-office.pengajuan.export') }}`;


            const queryString =
                params.toString();


            const exportUrl =
                queryString
                    ? `${baseUrl}?${queryString}`
                    : baseUrl;


            /*
            |--------------------------------------------------------------------------
            | TUTUP MODAL
            |--------------------------------------------------------------------------
            */

            const modalElement =
                document.getElementById(
                    'modalExportPengajuan'
                );


            const modal =
                bootstrap.Modal.getInstance(
                    modalElement
                );


            if (modal) {

                modal.hide();

            }


            /*
            |--------------------------------------------------------------------------
            | DOWNLOAD FILE
            |--------------------------------------------------------------------------
            */

            window.location.href =
                exportUrl;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | RESET FILTER EXPORT
    |--------------------------------------------------------------------------
    */

    $('#btnResetExport').on(
        'click',
        function () {

            $('#formExportPengajuan')[0].reset();

        }
    );

});


/*
|--------------------------------------------------------------------------
| UPDATE STATUS PENGAJUAN
|--------------------------------------------------------------------------
*/

function updatePengajuanStatus(
    id,
    status
) {

    const isApprove =
        status === 'Diterima';


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI TERIMA
    |--------------------------------------------------------------------------
    */

    if (isApprove) {

        Swal.fire({

            icon: 'question',

            title: 'Terima Pengajuan?',

            text:
                'Pengajuan ini akan diterima dan akun peserta akan dibuat.',

            showCancelButton: true,

            confirmButtonText: `

                <i class="bi bi-check-circle me-1"></i>

                Ya, Terima

            `,

            cancelButtonText: `

                <i class="bi bi-x-circle me-1"></i>

                Batal

            `,

            confirmButtonColor:
                '#198754',

            cancelButtonColor:
                '#6c757d',

            reverseButtons: true

        }).then(function (result) {

            if (!result.isConfirmed) {

                return;

            }


            prosesUpdatePengajuan(
                id,
                status
            );

        });


        return;

    }


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI TOLAK + CATATAN
    |--------------------------------------------------------------------------
    */

    Swal.fire({

        icon: 'warning',

        title: 'Tolak Pengajuan?',

        text:
            'Pengajuan yang sudah ditolak tidak dapat diubah kembali.',

        input: 'textarea',

        inputPlaceholder:
            'Masukkan catatan penolakan (opsional)...',

        inputAttributes: {

            'aria-label':
                'Catatan penolakan'

        },

        showCancelButton: true,

        confirmButtonText: `

            <i class="bi bi-x-circle me-1"></i>

            Ya, Tolak

        `,

        cancelButtonText: `

            <i class="bi bi-arrow-left me-1"></i>

            Batal

        `,

        confirmButtonColor:
            '#dc3545',

        cancelButtonColor:
            '#6c757d',

        reverseButtons: true

    }).then(function (result) {

        if (!result.isConfirmed) {

            return;

        }


        prosesUpdatePengajuan(
            id,
            status,
            result.value || null
        );

    });

}


/*
|--------------------------------------------------------------------------
| REQUEST UPDATE
|--------------------------------------------------------------------------
*/

function prosesUpdatePengajuan(
    id,
    status,
    catatan = null
) {

    Swal.fire({

        title: 'Memproses...',

        text:
            'Mohon tunggu sebentar.',

        allowOutsideClick: false,

        allowEscapeKey: false,

        didOpen: function () {

            Swal.showLoading();

        }

    });


    const payload = {

        status: status

    };


    if (catatan !== null) {

        payload.catatan =
            catatan;

    }


    $.ajax({

        url:
            `/api/back-office/pengajuan/${id}`,

        type:
            'PUT',

        contentType:
            'application/json',

        data:
            JSON.stringify(payload),

        headers: {

            'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]')
                    .attr('content')

        },


        success: function (response) {

            if (tablePengajuan) {

                tablePengajuan.ajax.reload(
                    null,
                    false
                );

            }


            Swal.fire({

                icon:
                    'success',

                title:
                    'Berhasil!',

                text:
                    response.message ||

                    (
                        status === 'Diterima'
                            ? 'Pengajuan berhasil diterima.'
                            : 'Pengajuan berhasil ditolak.'
                    ),

                timer:
                    1800,

                showConfirmButton:
                    false

            });

        },


        error: function (xhr) {

            console.error(
                'Update Pengajuan Error:',
                xhr
            );


            let message =
                'Terjadi kesalahan saat memproses pengajuan.';


            if (
                xhr.responseJSON &&
                xhr.responseJSON.message
            ) {

                message =
                    xhr.responseJSON.message;

            }


            /*
            |--------------------------------------------------------------------------
            | VALIDATION ERROR
            |--------------------------------------------------------------------------
            */

            if (
                xhr.status === 422 &&
                xhr.responseJSON?.errors
            ) {

                const errors =
                    xhr.responseJSON.errors;


                message =
                    Object.values(errors)
                        .flat()
                        .join('<br>');

            }


            Swal.fire({

                icon:
                    'error',

                title:
                    'Gagal!',

                html:
                    message,

                confirmButtonText:
                    'OK'

            });

        }

    });

}


/*
|--------------------------------------------------------------------------
| ARSIPKAN PENGAJUAN
|--------------------------------------------------------------------------
*/

function archivePengajuan(id)
{

    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI
    |--------------------------------------------------------------------------
    */

    Swal.fire({

        icon: 'warning',

        title: 'Arsipkan Pengajuan?',

        text:
            'Pengajuan yang sudah diarsipkan akan dipindahkan dari halaman pengajuan aktif ke halaman arsip.',

        showCancelButton: true,

        confirmButtonText: `

            <i class="bi bi-archive me-1"></i>

            Ya, Arsipkan

        `,

        cancelButtonText: `

            <i class="bi bi-arrow-left me-1"></i>

            Batal

        `,

        confirmButtonColor:
            '#0d6efd',

        cancelButtonColor:
            '#6c757d',

        reverseButtons: true

    }).then(function (result) {

        if (!result.isConfirmed) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | LOADING
        |--------------------------------------------------------------------------
        */

        Swal.fire({

            title:
                'Mengarsipkan...',

            text:
                'Mohon tunggu sebentar.',

            allowOutsideClick:
                false,

            allowEscapeKey:
                false,

            didOpen: function () {

                Swal.showLoading();

            }

        });


        /*
        |--------------------------------------------------------------------------
        | REQUEST
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url:
                `/api/back-office/pengajuan/${id}/archive`,

            type:
                'PUT',

            contentType:
                'application/json',

            headers: {

                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]')
                        .attr('content')

            },


            success: function (response) {

                /*
                |--------------------------------------------------------------------------
                | RELOAD DATATABLE
                |--------------------------------------------------------------------------
                */

                if (tablePengajuan) {

                    tablePengajuan.ajax.reload(
                        null,
                        false
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | SUCCESS
                |--------------------------------------------------------------------------
                */

                Swal.fire({

                    icon:
                        'success',

                    title:
                        'Berhasil!',

                    text:
                        response.message ||
                        'Pengajuan berhasil diarsipkan.',

                    timer:
                        1800,

                    showConfirmButton:
                        false

                });

            },


            error: function (xhr) {

                console.error(
                    'Archive Pengajuan Error:',
                    xhr
                );


                let message =
                    'Terjadi kesalahan saat mengarsipkan pengajuan.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                Swal.fire({

                    icon:
                        'error',

                    title:
                        'Gagal!',

                    text:
                        message,

                    confirmButtonText:
                        'OK'

                });

            }

        });

    });

}

</script>

@endpush