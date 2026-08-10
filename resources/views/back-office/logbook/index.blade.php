@extends('layouts.back-office')

@section('title', 'Monitoring Logbook')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            Monitoring Logbook
        </h3>

        <small class="text-muted">
            Monitoring seluruh logbook peserta magang.
        </small>
    </div>

    <div>
        <a
            href="{{ route('back-office.logbook.export') }}"
            class="btn btn-success"
        >
            <i class="bi bi-file-earmark-excel me-1"></i>
            Export Excel
        </a>
    </div>

</div>

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body">

        <div class="table-responsive">

            <table
                class="table table-bordered table-hover align-middle w-100"
                id="logbookTable"
            >

                <thead class="table-light">

                    <tr>

                        <th width="5%" class="text-center">
                            No
                        </th>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            Nama Peserta
                        </th>

                        <th>
                            Mentor
                        </th>

                        <th>
                            Aktivitas
                        </th>

                        <th>
                            Hasil
                        </th>

                        <th>
                            Catatan Peserta
                        </th>

                        <th>
                            Bukti Kegiatan
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Catatan Mentor
                        </th>

                        <th width="12%" class="text-center">
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
{{-- MODAL BUKTI KEGIATAN --}}
{{-- ========================================================= --}}

<div
    class="modal fade"
    id="modalBukti"
    tabindex="-1"
    aria-labelledby="modalBuktiLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow">

            <div class="modal-header">

                <div>

                    <h5
                        class="modal-title fw-bold"
                        id="modalBuktiLabel"
                    >
                        Bukti Kegiatan
                    </h5>

                    <small class="text-muted">
                        Bukti kegiatan logbook peserta.
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <div class="modal-body text-center p-4">

                {{-- Loading --}}

                <div
                    id="loadingBukti"
                    class="py-5"
                >

                    <div
                        class="spinner-border text-primary"
                        role="status"
                    ></div>

                    <div class="mt-2 text-muted">
                        Memuat bukti kegiatan...
                    </div>

                </div>


                {{-- Preview Gambar --}}

                <div
                    id="containerGambarBukti"
                    class="d-none"
                >

                    <img
                        id="gambarBukti"
                        src=""
                        alt="Bukti kegiatan"
                        class="img-fluid rounded border"
                        style="
                            max-height: 700px;
                            max-width: 100%;
                            object-fit: contain;
                        "
                    >

                </div>


                {{-- Preview PDF --}}

                <div
                    id="containerPdfBukti"
                    class="d-none"
                >

                    <iframe
                        id="pdfBukti"
                        src=""
                        title="Bukti kegiatan PDF"
                        style="
                            width:100%;
                            height:700px;
                            border:1px solid #dee2e6;
                            border-radius:12px;
                        "
                    ></iframe>

                </div>


                {{-- Error --}}

                <div
                    id="errorBukti"
                    class="alert alert-danger d-none mt-3"
                >

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    Bukti kegiatan tidak dapat ditampilkan.

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('js')

<script>

$(document).ready(function () {

    /* =========================================================
       HELPER ESCAPE HTML
    ========================================================= */

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }

        return $('<div>')
            .text(String(value))
            .html();

    }


    /* =========================================================
       HELPER ESCAPE ATTRIBUTE
    ========================================================= */

    function escapeAttribute(value) {

        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

    }


    /* =========================================================
       STATUS BADGE
    ========================================================= */

    function statusBadge(status) {

        const normalized = String(
            status || 'Menunggu'
        )
        .trim()
        .toLowerCase();


        if (
            normalized === 'disetujui' ||
            normalized === 'approved'
        ) {

            return `
                <span class="badge bg-success">
                    <i class="bi bi-check-circle me-1"></i>
                    Disetujui
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


    /* =========================================================
       INFORMASI APPROVAL
    ========================================================= */

    function approvalInfo(status) {

        const normalized = String(
            status || 'Menunggu'
        )
        .trim()
        .toLowerCase();


        if (
            normalized === 'disetujui' ||
            normalized === 'approved'
        ) {

            return `
                <span class="text-success small fw-semibold">
                    <i class="bi bi-check-circle me-1"></i>
                    Sudah di-approve
                </span>
            `;

        }


        if (
            normalized === 'ditolak' ||
            normalized === 'rejected'
        ) {

            return `
                <span class="text-danger small fw-semibold">
                    <i class="bi bi-x-circle me-1"></i>
                    Ditolak
                </span>
            `;

        }


        return `
            <span class="text-muted small">
                <i class="bi bi-clock me-1"></i>
                Belum di-approve
            </span>
        `;

    }


    /* =========================================================
       FORMAT TANGGAL
    ========================================================= */

    function formatTanggal(value) {

        if (!value) {
            return '-';
        }

        const date = new Date(value);

        if (isNaN(date.getTime())) {
            return escapeHtml(value);
        }

        const day = String(
            date.getDate()
        ).padStart(2, '0');

        const month = String(
            date.getMonth() + 1
        ).padStart(2, '0');

        const year = date.getFullYear();

        return `${day}-${month}-${year}`;

    }


    /* =========================================================
       DATA TABLE
    ========================================================= */

    const tableLogbook = $('#logbookTable').DataTable({

        destroy: true,

        processing: true,

        serverSide: false,

        responsive: false,

        autoWidth: false,

        pageLength: 10,

        ajax: {

            url: '/back-office/logbook/data',

            type: 'GET',

            dataSrc: function (response) {

                if (!response) {
                    return [];
                }


                if (
                    response.status &&
                    response.status !== 'success'
                ) {

                    return [];

                }


                if (Array.isArray(response.data)) {
                    return response.data;
                }


                return [];

            },

            error: function (xhr) {

                console.error(
                    'Gagal mengambil data logbook:',
                    xhr
                );


                let message =
                    'Gagal memuat data logbook.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                if (window.Swal) {

                    Swal.fire({

                        icon: 'error',

                        title: 'Gagal Memuat Data',

                        text: message

                    });

                }

            }

        },


        columns: [

            /* =================================================
               NO
            ================================================= */

            {

                data: null,

                className: 'text-center',

                orderable: false,

                searchable: false,

                width: '5%',

                render: function (
                    data,
                    type,
                    row,
                    meta
                ) {

                    return meta.row + 1;

                }

            },


            /* =================================================
               TANGGAL
            ================================================= */

            {

                data: 'tanggal',

                defaultContent: '-',

                render: function (data) {

                    return formatTanggal(data);

                }

            },


            /* =================================================
               NAMA PESERTA
            ================================================= */

            {

                data: 'nama_peserta',

                defaultContent: '-',

                render: function (data) {

                    if (!data) {
                        return '-';
                    }

                    return `
                        <span class="fw-semibold">
                            ${escapeHtml(data)}
                        </span>
                    `;

                }

            },


            /* =================================================
               MENTOR
            ================================================= */

            {

                data: 'mentor',

                defaultContent: '-',

                render: function (data) {

                    return data
                        ? escapeHtml(data)
                        : '-';

                }

            },


            /* =================================================
               AKTIVITAS
            ================================================= */

            {

                data: 'aktivitas',

                defaultContent: '-',

                render: function (data) {

                    if (!data) {
                        return '-';
                    }

                    return `
                        <div
                            style="
                                min-width:180px;
                                max-width:300px;
                                white-space:normal;
                                word-break:break-word;
                            "
                        >
                            ${escapeHtml(data)}
                        </div>
                    `;

                }

            },


            /* =================================================
               HASIL
            ================================================= */

            {

                data: 'hasil',

                defaultContent: '-',

                render: function (data) {

                    if (!data) {
                        return '-';
                    }

                    return `
                        <div
                            style="
                                min-width:180px;
                                max-width:300px;
                                white-space:normal;
                                word-break:break-word;
                            "
                        >
                            ${escapeHtml(data)}
                        </div>
                    `;

                }

            },


            /* =================================================
               CATATAN PESERTA
            ================================================= */

            {

                data: 'catatan',

                defaultContent: '-',

                render: function (data) {

                    if (!data) {

                        return `
                            <span class="text-muted">
                                Tidak ada catatan
                            </span>
                        `;

                    }


                    return `
                        <div
                            style="
                                min-width:180px;
                                max-width:300px;
                                white-space:normal;
                                word-break:break-word;
                            "
                        >
                            ${escapeHtml(data)}
                        </div>
                    `;

                }

            },


            /* =================================================
               BUKTI KEGIATAN
            ================================================= */

            {

                data: null,

                orderable: false,

                searchable: false,

                className: 'text-center',

                render: function (data) {

                    if (
                        !data ||
                        !data.bukti_url
                    ) {

                        return `
                            <span class="text-muted small">
                                <i class="bi bi-file-earmark-x me-1"></i>
                                Tidak ada
                            </span>
                        `;

                    }


                    return `
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary btn-bukti"
                            data-url="${escapeAttribute(data.bukti_url)}"
                        >
                            <i class="bi bi-eye me-1"></i>
                            Lihat
                        </button>
                    `;

                }

            },


            /* =================================================
               STATUS
            ================================================= */

            {

                data: 'status',

                className: 'text-center',

                render: function (data) {

                    return statusBadge(data);

                }

            },


            /* =================================================
               CATATAN MENTOR
            ================================================= */

            {

                data: 'catatan_mentor',

                defaultContent: '-',

                render: function (data) {

                    if (!data) {

                        return `
                            <span class="text-muted small">
                                Belum ada catatan
                            </span>
                        `;

                    }


                    return `
                        <div
                            style="
                                min-width:180px;
                                max-width:300px;
                                white-space:normal;
                                word-break:break-word;
                            "
                        >
                            ${escapeHtml(data)}
                        </div>
                    `;

                }

            },


            /* =================================================
               AKSI
            ================================================= */

            {

                data: 'status',

                orderable: false,

                searchable: false,

                className: 'text-center',

                render: function (data) {

                    return approvalInfo(data);

                }

            }

        ],


        /* =====================================================
           DEFAULT ORDER
        ===================================================== */

        order: [

            [1, 'desc']

        ],


        /* =====================================================
           BAHASA DATATABLE
        ===================================================== */

        language: {

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

            infoFiltered:
                '(difilter dari _MAX_ total data)',

            zeroRecords:
                'Data logbook tidak ditemukan',

            emptyTable:
                'Belum ada data logbook',

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


    /* =========================================================
       LIHAT BUKTI KEGIATAN
    ========================================================= */

    $(document).on(
        'click',
        '.btn-bukti',
        function () {

            const url =
                $(this).attr('data-url');


            if (!url) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Bukti Tidak Tersedia',

                    text:
                        'File bukti kegiatan tidak ditemukan.'

                });

                return;

            }


            /* =================================================
               RESET MODAL
            ================================================= */

            $('#loadingBukti')
                .removeClass('d-none');


            $('#containerGambarBukti')
                .addClass('d-none');


            $('#containerPdfBukti')
                .addClass('d-none');


            $('#errorBukti')
                .addClass('d-none');


            $('#gambarBukti')
                .attr('src', '');


            $('#pdfBukti')
                .attr('src', 'about:blank');


            /* =================================================
               BUKA MODAL
            ================================================= */

            const modalElement =
                document.getElementById(
                    'modalBukti'
                );


            const modal =
                bootstrap.Modal.getOrCreateInstance(
                    modalElement
                );


            modal.show();


            /* =================================================
               TENTUKAN TIPE FILE
            ================================================= */

            const cleanUrl =
                url.split('?')[0]
                   .split('#')[0];


            const extension =
                cleanUrl
                    .split('.')
                    .pop()
                    .toLowerCase();


            const imageExtensions = [

                'jpg',
                'jpeg',
                'png',
                'gif',
                'webp',
                'bmp'

            ];


            const isImage =
                imageExtensions.includes(
                    extension
                );


            const isPdf =
                extension === 'pdf';


            /* =================================================
               PREVIEW GAMBAR
            ================================================= */

            if (isImage) {

                const image =
                    new Image();


                image.onload = function () {

                    $('#loadingBukti')
                        .addClass('d-none');


                    $('#errorBukti')
                        .addClass('d-none');


                    $('#containerGambarBukti')
                        .removeClass('d-none');


                    $('#gambarBukti')
                        .attr('src', url);

                };


                image.onerror = function () {

                    $('#loadingBukti')
                        .addClass('d-none');


                    $('#containerGambarBukti')
                        .addClass('d-none');


                    $('#errorBukti')
                        .removeClass('d-none');


                    console.error(
                        'Bukti gambar gagal dimuat:',
                        url
                    );

                };


                image.src = url;


                return;

            }


            /* =================================================
               PREVIEW PDF
            ================================================= */

            if (isPdf) {

                $('#loadingBukti')
                    .addClass('d-none');


                $('#containerPdfBukti')
                    .removeClass('d-none');


                $('#pdfBukti')
                    .attr('src', url);


                return;

            }


            /* =================================================
               FORMAT TIDAK DIDUKUNG
            ================================================= */

            $('#loadingBukti')
                .addClass('d-none');


            $('#errorBukti')
                .removeClass('d-none')
                .html(`
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Format file bukti kegiatan
                    tidak dapat dipratinjau.
                    <div class="mt-2">
                        <a
                            href="${escapeAttribute(url)}"
                            target="_blank"
                            class="btn btn-sm btn-danger"
                        >
                            <i class="bi bi-box-arrow-up-right me-1"></i>
                            Buka File
                        </a>
                    </div>
                `);

        }
    );


    /* =========================================================
       RESET KETIKA MODAL DITUTUP
    ========================================================= */

    $('#modalBukti').on(
        'hidden.bs.modal',
        function () {

            $('#gambarBukti')
                .attr('src', '');


            $('#pdfBukti')
                .attr('src', 'about:blank');


            $('#containerGambarBukti')
                .addClass('d-none');


            $('#containerPdfBukti')
                .addClass('d-none');


            $('#errorBukti')
                .addClass('d-none');


            $('#loadingBukti')
                .removeClass('d-none');

        }
    );


});

</script>

@endpush