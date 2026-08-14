@extends('layouts.back-office')

@section('title', 'Arsip Pengajuan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            Arsip Pengajuan
        </h3>

        <small class="text-muted">
            Daftar pengajuan magang yang telah diarsipkan.
        </small>
    </div>

</div>


{{-- ========================================================= --}}
{{-- TABLE ARSIP --}}
{{-- ========================================================= --}}

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-3 p-md-4">

        <div class="table-responsive">

            <table
                id="tableArsipPengajuan"
                class="table table-bordered table-hover align-middle w-100"
            >

                <thead class="table-light">

                    <tr>

                        <th class="text-center text-nowrap">
                            No
                        </th>

                        <th class="text-center text-nowrap">
                            Kode Pengajuan
                        </th>

                        <th class="text-nowrap">
                            Perguruan Tinggi
                        </th>

                        <th class="text-center text-nowrap">
                            Periode
                        </th>

                        <th class="text-center text-nowrap">
                            Status
                        </th>

                        <th class="text-center text-nowrap">
                            Diarsipkan
                        </th>

                        <th class="text-center text-nowrap">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody></tbody>

            </table>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL DETAIL ARSIP --}}
{{-- ========================================================= --}}

<div
    class="modal fade"
    id="modalDetailArsip"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content border-0 shadow">


            {{-- ================================================= --}}
            {{-- MODAL HEADER --}}
            {{-- ================================================= --}}

            <div class="modal-header">

                <div>

                    <h5 class="modal-title fw-bold">
                        Detail Arsip Pengajuan
                    </h5>

                    <small
                        class="text-muted"
                        id="detailKodePengajuan"
                    ></small>

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


                {{-- ================================================= --}}
                {{-- DATA PENGAJUAN --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <h6 class="fw-bold border-bottom pb-2 mb-3">

                        <i class="bi bi-file-earmark-text me-1"></i>

                        Data Pengajuan

                    </h6>


                    <div class="table-responsive">

                        <table class="table table-bordered align-middle mb-0">

                            <tbody>


                                {{-- KODE PENGAJUAN --}}

                                <tr>

                                    <th
                                        class="table-light text-nowrap"
                                        style="width:220px;"
                                    >
                                        Kode Pengajuan
                                    </th>

                                    <td id="detailKode">
                                        -
                                    </td>

                                </tr>


                                {{-- STATUS --}}

                                <tr>

                                    <th class="table-light text-nowrap">
                                        Status
                                    </th>

                                    <td id="detailStatus">
                                        -
                                    </td>

                                </tr>


                                {{-- PERGURUAN TINGGI --}}

                                <tr>

                                    <th class="table-light text-nowrap">
                                        Perguruan Tinggi
                                    </th>

                                    <td id="detailUniversitas">
                                        -
                                    </td>

                                </tr>


                                {{-- SEMESTER --}}

                                <tr>

                                    <th class="table-light text-nowrap">
                                        Semester
                                    </th>

                                    <td id="detailSemester">
                                        -
                                    </td>

                                </tr>


                                {{-- PERIODE MAGANG --}}

                                <tr>

                                    <th class="table-light text-nowrap">
                                        Periode Magang
                                    </th>

                                    <td id="detailPeriode">
                                        -
                                    </td>

                                </tr>


                                {{-- DIARSIPKAN --}}

                                <tr>

                                    <th class="table-light text-nowrap">
                                        Diarsipkan
                                    </th>

                                    <td id="detailArchived">
                                        -
                                    </td>

                                </tr>


                                {{-- CATATAN --}}

                                <tr>

                                    <th class="table-light text-nowrap">
                                        Catatan
                                    </th>

                                    <td
                                        id="detailCatatan"
                                        style="white-space: pre-line;"
                                    >
                                        -
                                    </td>

                                </tr>


                                {{-- PROPOSAL --}}

                                <tr>

                                    <th class="table-light text-nowrap">
                                        Proposal
                                    </th>

                                    <td id="detailProposal">
                                        -
                                    </td>

                                </tr>


                                {{-- SURAT PERMOHONAN --}}

                                <tr>

                                    <th class="table-light text-nowrap">
                                        Surat Permohonan
                                    </th>

                                    <td id="detailSuratPermohonan">
                                        -
                                    </td>

                                </tr>


                                {{-- SURAT PENERIMAAN --}}

                                <tr>

                                    <th class="table-light text-nowrap">
                                        Surat Penerimaan
                                    </th>

                                    <td id="detailSuratPenerimaan">
                                        -
                                    </td>

                                </tr>


                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- DATA PESERTA --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <h6 class="fw-bold border-bottom pb-2 mb-3">

                        <i class="bi bi-people me-1"></i>

                        Data Peserta

                    </h6>


                    <div class="table-responsive">

                        <table
                            class="table table-bordered table-hover align-middle"
                        >

                            <thead class="table-light">

                                <tr>

                                    <th
                                        class="text-center"
                                        style="width:60px;"
                                    >
                                        No
                                    </th>

                                    <th>
                                        Nama Peserta
                                    </th>

                                    <th>
                                        Email
                                    </th>

                                    <th>
                                        No. HP
                                    </th>

                                    <th>
                                        Mentor
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="detailPeserta">

                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center text-muted py-4"
                                    >
                                        Belum ada data peserta.
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- LOGBOOK --}}
                {{-- ================================================= --}}

                <div>

                    <h6 class="fw-bold border-bottom pb-2 mb-3">

                        <i class="bi bi-journal-text me-1"></i>

                        Riwayat Logbook

                    </h6>


                    <div class="table-responsive">

                        <table
                            class="table table-bordered table-hover align-middle"
                        >

                            <thead class="table-light">

                                <tr>

                                    <th
                                        class="text-center"
                                        style="width:60px;"
                                    >
                                        No
                                    </th>

                                    <th>
                                        Tanggal
                                    </th>

                                    <th>
                                        Peserta
                                    </th>

                                    <th>
                                        Aktivitas
                                    </th>

                                    <th>
                                        Hasil
                                    </th>

                                    <th class="text-center">
                                        Status
                                    </th>

                                    <th
                                        class="text-center"
                                        style="width:100px;"
                                    >
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="detailLogbook">

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center text-muted py-4"
                                    >
                                        Belum ada riwayat logbook.
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- MODAL FOOTER --}}
            {{-- ================================================= --}}

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Tutup
                </button>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL DETAIL LOGBOOK --}}
{{-- ========================================================= --}}

<div
    class="modal fade"
    id="modalDetailLogbook"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-scrollable">

        <div class="modal-content border-0 shadow">


            {{-- MODAL HEADER --}}

            <div class="modal-header">

                <div>

                    <h5 class="modal-title fw-bold">
                        Detail Logbook
                    </h5>

                    <small
                        class="text-muted"
                        id="logbookPeserta"
                    ></small>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            {{-- MODAL BODY --}}

            <div class="modal-body">

                <div class="table-responsive">

                    <table class="table table-bordered align-middle">

                        <tbody>


                            <tr>

                                <th
                                    class="table-light"
                                    style="width:200px;"
                                >
                                    Peserta
                                </th>

                                <td id="logbookNamaPeserta">
                                    -
                                </td>

                            </tr>


                            <tr>

                                <th class="table-light">
                                    Email
                                </th>

                                <td id="logbookEmailPeserta">
                                    -
                                </td>

                            </tr>


                            <tr>

                                <th class="table-light">
                                    Tanggal
                                </th>

                                <td id="logbookTanggal">
                                    -
                                </td>

                            </tr>


                            <tr>

                                <th class="table-light">
                                    Status
                                </th>

                                <td id="logbookStatus">
                                    -
                                </td>

                            </tr>


                            <tr>

                                <th class="table-light">
                                    Aktivitas
                                </th>

                                <td id="logbookAktivitas">
                                    -
                                </td>

                            </tr>


                            <tr>

                                <th class="table-light">
                                    Hasil
                                </th>

                                <td id="logbookHasil">
                                    -
                                </td>

                            </tr>


                            <tr>

                                <th class="table-light">
                                    Catatan Peserta
                                </th>

                                <td id="logbookCatatan">
                                    -
                                </td>

                            </tr>


                            <tr>

                                <th class="table-light">
                                    Catatan Mentor
                                </th>

                                <td id="logbookCatatanMentor">
                                    -
                                </td>

                            </tr>


                            <tr>

                                <th class="table-light">
                                    Bukti
                                </th>

                                <td id="logbookBukti">
                                    -
                                </td>

                            </tr>


                        </tbody>

                    </table>

                </div>

            </div>


            {{-- MODAL FOOTER --}}

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Tutup
                </button>

            </div>

        </div>

    </div>

</div>

@endsection


@push('js')

<script>

$(function () {


    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

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


    function formatTanggal(value) {

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


    function formatTanggalJam(value) {

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

    function statusBadge(status) {

        const value =
            String(status || '')
                .toLowerCase()
                .trim();


        if (
            value === 'diterima' ||
            value === 'disetujui'
        ) {

            return `
                <span class="badge bg-success">

                    <i class="bi bi-check-circle me-1"></i>

                    ${escapeHtml(status)}

                </span>
            `;

        }


        if (value === 'ditolak') {

            return `
                <span class="badge bg-danger">

                    <i class="bi bi-x-circle me-1"></i>

                    ${escapeHtml(status)}

                </span>
            `;

        }


        if (value === 'pending') {

            return `
                <span class="badge bg-warning text-dark">

                    <i class="bi bi-clock me-1"></i>

                    ${escapeHtml(status)}

                </span>
            `;

        }


        return `
            <span class="badge bg-secondary">

                ${escapeHtml(status || '-')}

            </span>
        `;

    }


    /*
    |--------------------------------------------------------------------------
    | FILE ROUTE
    |--------------------------------------------------------------------------
    */

    const fileRoute =
        @json(url('/back-office/arsip-pengajuan'));


    function fileButton(
        path,
        label,
        pengajuanId,
        type
    ) {

        if (!path) {

            return `
                <span class="text-muted">

                    <i class="bi bi-file-earmark-x me-1"></i>

                    Tidak tersedia

                </span>
            `;

        }


        const url =
            fileRoute +
            '/' +
            encodeURIComponent(pengajuanId) +
            '/file/' +
            encodeURIComponent(type);


        return `
            <a
                href="${escapeHtml(url)}"
                target="_blank"
                rel="noopener noreferrer"
                class="btn btn-sm btn-outline-primary"
            >

                <i class="bi bi-eye me-1"></i>

                Lihat ${escapeHtml(label)}

            </a>
        `;

    }


    /*
    |--------------------------------------------------------------------------
    | DATA TABLE
    |--------------------------------------------------------------------------
    */

    const table =
        $('#tableArsipPengajuan').DataTable({

            processing: true,

            serverSide: false,

            autoWidth: false,

            responsive: false,

            scrollX: true,

            pageLength: 10,


            ajax: {

                url:
                    @json(url('/back-office/arsip-pengajuan/data')),

                type: 'GET',


                dataSrc: function(response) {

                    console.log(
                        'DATA ARSIP:',
                        response
                    );

                    return response.data || [];

                },


                error: function(xhr) {

                    console.error(
                        'Arsip Pengajuan AJAX Error:',
                        xhr.responseText
                    );


                    Swal.fire({

                        icon: 'error',

                        title: 'Gagal Memuat Data',

                        text:
                            xhr.responseJSON?.message ||
                            'Gagal memuat arsip pengajuan.'

                    });

                }

            },


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

                    render: function(
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

                    render: function(data) {

                        return `
                            <span class="fw-semibold">

                                ${escapeHtml(data)}

                            </span>
                        `;

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | UNIVERSITAS
                |--------------------------------------------------------------------------
                */

                {

                    data: 'universitas',

                    render: function(data) {

                        return escapeHtml(data);

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | PERIODE
                |--------------------------------------------------------------------------
                */

                {

                    data: null,

                    className:
                        'text-center text-nowrap',

                    render: function(data) {

                        return `
                            ${formatTanggal(data.tanggal_mulai)}

                            -

                            ${formatTanggal(data.tanggal_selesai)}
                        `;

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

                    render: function(data) {

                        return statusBadge(data);

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | DIARSIPKAN
                |--------------------------------------------------------------------------
                */

                {

                    data: 'archived_at',

                    className:
                        'text-center text-nowrap',

                    render: function(data) {

                        return formatTanggalJam(data);

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | AKSI
                |--------------------------------------------------------------------------
                */

                {

                    data: null,

                    className:
                        'text-center text-nowrap',

                    orderable: false,

                    searchable: false,

                    render: function(
                        data,
                        type,
                        row
                    ) {

                        return `

                            <div
                                class="d-flex justify-content-center align-items-center gap-1"
                            >


                                {{-- DETAIL --}}

                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary btn-detail-arsip"
                                    data-id="${escapeHtml(row.id)}"
                                    title="Lihat Detail"
                                >

                                    <i class="bi bi-eye me-1"></i>

                                    Detail

                                </button>


                                {{-- EXPORT EXCEL --}}

                                <button
                                    type="button"
                                    class="btn btn-sm btn-success btn-export-arsip"
                                    data-id="${escapeHtml(row.id)}"
                                    title="Export Excel"
                                >

                                    <i class="bi bi-file-earmark-excel me-1"></i>

                                    Excel

                                </button>


                            </div>

                        `;

                    }

                }

            ],


            language: {

                emptyTable:
                    'Belum ada pengajuan yang diarsipkan.',

                processing:
                    'Memuat data...',

                search:
                    'Cari:',

                lengthMenu:
                    'Tampilkan _MENU_ data',

                info:
                    'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',

                infoEmpty:
                    'Tidak ada data',

                zeroRecords:
                    'Arsip pengajuan tidak ditemukan.',

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
    | DETAIL ARSIP
    |--------------------------------------------------------------------------
    */

    $('#tableArsipPengajuan')
        .on(
            'click',
            '.btn-detail-arsip',
            function()
        {

            const id =
                Number(
                    $(this).attr('data-id')
                );


            const data =
                table
                    .rows()
                    .data()
                    .toArray()
                    .find(function(row) {

                        return Number(row.id) === id;

                    });


            if (!data) {

                Swal.fire(
                    'Data Tidak Ditemukan',
                    'Data arsip tidak ditemukan.',
                    'error'
                );

                return;

            }


            console.log(
                'DETAIL PENGAJUAN:',
                data
            );


            /*
            |--------------------------------------------------------------------------
            | DATA PENGAJUAN
            |--------------------------------------------------------------------------
            */

            $('#detailKodePengajuan')
                .text(
                    data.kode_pengajuan || '-'
                );


            $('#detailKode')
                .text(
                    data.kode_pengajuan || '-'
                );


            $('#detailStatus')
                .html(
                    statusBadge(data.status)
                );


            $('#detailUniversitas')
                .text(
                    data.universitas || '-'
                );


            $('#detailSemester')
                .text(
                    data.semester || '-'
                );


            $('#detailPeriode')
                .html(`
                    ${formatTanggal(data.tanggal_mulai)}

                    -

                    ${formatTanggal(data.tanggal_selesai)}
                `);


            $('#detailArchived')
                .text(
                    formatTanggalJam(
                        data.archived_at
                    )
                );


            $('#detailCatatan')
                .text(
                    data.catatan || '-'
                );


            /*
            |--------------------------------------------------------------------------
            | DOKUMEN
            |--------------------------------------------------------------------------
            */

            $('#detailProposal')
                .html(
                    fileButton(
                        data.proposal,
                        'Proposal',
                        data.id,
                        'proposal'
                    )
                );


            $('#detailSuratPermohonan')
                .html(
                    fileButton(
                        data.surat_permohonan,
                        'Surat Permohonan',
                        data.id,
                        'surat-permohonan'
                    )
                );


            $('#detailSuratPenerimaan')
                .html(
                    fileButton(
                        data.surat_penerimaan,
                        'Surat Penerimaan',
                        data.id,
                        'surat-penerimaan'
                    )
                );


            /*
            |--------------------------------------------------------------------------
            | PESERTA
            |--------------------------------------------------------------------------
            */

            let peserta =
                Array.isArray(data.peserta)
                    ? data.peserta
                    : (
                        Array.isArray(data.anggota)
                            ? data.anggota
                            : []
                    );


            let pesertaHtml = '';


            if (peserta.length === 0) {

                pesertaHtml = `

                    <tr>

                        <td
                            colspan="5"
                            class="text-center text-muted py-4"
                        >

                            <i
                                class="bi bi-people fs-4 d-block mb-2"
                            ></i>

                            Tidak ada data peserta.

                        </td>

                    </tr>

                `;

            } else {

                peserta.forEach(
                    function(
                        pesertaItem,
                        index
                    )
                {

                    const namaPeserta =
                        pesertaItem.nama ??
                        pesertaItem.nama_anggota ??
                        pesertaItem.name ??
                        '-';


                    const emailPeserta =
                        pesertaItem.email ??
                        '-';


                    const noHpPeserta =
                        pesertaItem.no_hp ??
                        '-';


                    let namaMentor =
                        pesertaItem.mentor ??
                        pesertaItem.nama_mentor ??
                        '';


                    if (
                        typeof namaMentor === 'object' &&
                        namaMentor !== null
                    ) {

                        namaMentor =
                            namaMentor.nama ??
                            namaMentor.name ??
                            '';

                    }


                    if (!namaMentor) {

                        if (
                            data.mentor &&
                            typeof data.mentor === 'object'
                        ) {

                            namaMentor =
                                data.mentor.nama ??
                                data.mentor.name ??
                                '';

                        } else {

                            namaMentor =
                                data.nama_mentor ??
                                '';

                        }

                    }


                    pesertaHtml += `

                        <tr>

                            <td class="text-center">
                                ${index + 1}
                            </td>


                            <td class="fw-semibold">
                                ${escapeHtml(namaPeserta)}
                            </td>


                            <td>
                                ${escapeHtml(emailPeserta)}
                            </td>


                            <td>
                                ${escapeHtml(noHpPeserta)}
                            </td>


                            <td>

                                ${
                                    namaMentor
                                        ? `

                                            <span class="fw-semibold">

                                                <i
                                                    class="bi bi-person-badge me-1"
                                                ></i>

                                                ${escapeHtml(
                                                    namaMentor
                                                )}

                                            </span>

                                        `
                                        : `

                                            <span class="text-muted">

                                                Belum ada mentor

                                            </span>

                                        `
                                }

                            </td>

                        </tr>

                    `;

                });

            }


            $('#detailPeserta')
                .html(
                    pesertaHtml
                );


            /*
            |--------------------------------------------------------------------------
            | LOGBOOK
            |--------------------------------------------------------------------------
            */

            const logbooks =
                Array.isArray(data.logbooks)
                    ? data.logbooks
                    : [];


            let logbookHtml = '';


            if (logbooks.length === 0) {

                logbookHtml = `

                    <tr>

                        <td
                            colspan="7"
                            class="text-center text-muted py-4"
                        >

                            <i
                                class="bi bi-journal-x fs-4 d-block mb-2"
                            ></i>

                            Belum ada riwayat logbook.

                        </td>

                    </tr>

                `;

            } else {

                logbooks.forEach(
                    function(
                        logbook,
                        index
                    )
                {

                    const user =
                        logbook.user ||
                        {};


                    const namaPeserta =
                        user.name ??
                        logbook.nama_peserta ??
                        logbook.nama_user ??
                        'Peserta';


                    const aktivitas =
                        logbook.aktivitas ??
                        '-';


                    const hasil =
                        logbook.hasil ??
                        '-';


                    logbookHtml += `

                        <tr>

                            <td class="text-center">
                                ${index + 1}
                            </td>


                            <td class="text-nowrap">

                                ${formatTanggal(
                                    logbook.tanggal
                                )}

                            </td>


                            <td class="fw-semibold">

                                ${escapeHtml(
                                    namaPeserta
                                )}

                            </td>


                            <td>

                                ${escapeHtml(
                                    aktivitas
                                )}

                            </td>


                            <td>

                                ${escapeHtml(
                                    hasil
                                )}

                            </td>


                            <td class="text-center">

                                ${statusBadge(
                                    logbook.status
                                )}

                            </td>


                            <td class="text-center">

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary btn-detail-logbook"
                                    data-logbook-index="${index}"
                                >

                                    <i
                                        class="bi bi-eye me-1"
                                    ></i>

                                    Detail

                                </button>

                            </td>

                        </tr>

                    `;

                });

            }


            $('#detailLogbook')
                .html(
                    logbookHtml
                );


            $('#detailLogbook')
                .data(
                    'logbooks',
                    logbooks
                );


            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN MODAL
            |--------------------------------------------------------------------------
            */

            const modalElement =
                document.getElementById(
                    'modalDetailArsip'
                );


            const modal =
                bootstrap.Modal.getOrCreateInstance(
                    modalElement
                );


            modal.show();

        });


    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    |
    | SEKARANG TOMBOL EXPORT BERADA DI KOLOM AKSI.
    | TIDAK LAGI BERADA DI DALAM MODAL DETAIL.
    |
    */

    $('#tableArsipPengajuan')
        .on(
            'click',
            '.btn-export-arsip',
            function()
        {

            const id =
                $(this).attr('data-id');


            if (!id) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Data Tidak Ditemukan',

                    text:
                        'ID arsip tidak ditemukan.'

                });

                return;

            }


            const exportUrl =
                @json(url('/back-office/arsip-pengajuan'))
                +
                '/'
                +
                encodeURIComponent(id)
                +
                '/export';


            console.log(
                'EXPORT ARSIP:',
                exportUrl
            );


            /*
            |--------------------------------------------------------------------------
            | DOWNLOAD EXCEL
            |--------------------------------------------------------------------------
            */

            window.location.href =
                exportUrl;

        });


    /*
    |--------------------------------------------------------------------------
    | DETAIL LOGBOOK
    |--------------------------------------------------------------------------
    */

    $('#detailLogbook')
        .on(
            'click',
            '.btn-detail-logbook',
            function()
        {

            const index =
                Number(
                    $(this)
                        .attr(
                            'data-logbook-index'
                        )
                );


            const logbooks =
                $('#detailLogbook')
                    .data(
                        'logbooks'
                    ) || [];


            const logbook =
                logbooks[index];


            if (!logbook) {

                Swal.fire(
                    'Data Tidak Ditemukan',
                    'Data logbook tidak ditemukan.',
                    'error'
                );

                return;

            }


            const user =
                logbook.user ||
                {};


            const namaPeserta =
                user.name ??
                logbook.nama_peserta ??
                'Peserta';


            const emailPeserta =
                user.email ??
                logbook.email_peserta ??
                '-';


            $('#logbookPeserta')
                .text(
                    namaPeserta
                );


            $('#logbookNamaPeserta')
                .text(
                    namaPeserta
                );


            $('#logbookEmailPeserta')
                .text(
                    emailPeserta
                );


            $('#logbookTanggal')
                .text(
                    formatTanggal(
                        logbook.tanggal
                    )
                );


            $('#logbookStatus')
                .html(
                    statusBadge(
                        logbook.status
                    )
                );


            $('#logbookAktivitas')
                .text(
                    logbook.aktivitas ||
                    '-'
                );


            $('#logbookHasil')
                .text(
                    logbook.hasil ||
                    '-'
                );


            $('#logbookCatatan')
                .text(
                    logbook.catatan ||
                    'Tidak ada catatan peserta.'
                );


            $('#logbookCatatanMentor')
                .text(
                    logbook.catatan_mentor ||
                    'Tidak ada catatan mentor.'
                );


            /*
            |--------------------------------------------------------------------------
            | BUKTI
            |--------------------------------------------------------------------------
            */

            if (logbook.bukti) {

                const cleanBukti =
                    String(
                        logbook.bukti
                    )
                    .replace(
                        /^\/+/,
                        ''
                    );


                const buktiUrl =
                    @json(url('/storage'))
                    +
                    '/'
                    +
                    cleanBukti;


                $('#logbookBukti')
                    .html(`

                        <a
                            href="${escapeHtml(buktiUrl)}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-sm btn-outline-primary"
                        >

                            <i
                                class="bi bi-paperclip me-1"
                            ></i>

                            Lihat Bukti

                        </a>

                    `);

            } else {

                $('#logbookBukti')
                    .html(`

                        <span class="text-muted">

                            Tidak ada bukti.

                        </span>

                    `);

            }


            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN MODAL LOGBOOK
            |--------------------------------------------------------------------------
            */

            const modalElement =
                document.getElementById(
                    'modalDetailLogbook'
                );


            const modal =
                bootstrap.Modal.getOrCreateInstance(
                    modalElement
                );


            modal.show();

        });


    /*
    |--------------------------------------------------------------------------
    | AGAR MODAL DETAIL ARSIP TETAP TERBUKA
    | SAAT MODAL LOGBOOK DITUTUP
    |--------------------------------------------------------------------------
    */

    $('#modalDetailLogbook')
        .on(
            'hidden.bs.modal',
            function()
        {

            const modalArsip =
                document.getElementById(
                    'modalDetailArsip'
                );


            if (
                modalArsip &&
                modalArsip.classList.contains(
                    'show'
                )
            ) {

                document.body.classList.add(
                    'modal-open'
                );

            }

        });


});

</script>

@endpush