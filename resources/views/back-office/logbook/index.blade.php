@extends('layouts.back-office')

@section('title', 'Monitoring Logbook')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Monitoring Logbook
            </h3>

            <small class="text-muted">
                Monitoring seluruh logbook peserta magang.
            </small>
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

</div>


{{-- =========================================================
MODAL BUKTI KEGIATAN
========================================================= --}}

<div
    class="modal fade"
    id="modalBukti"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow">

            <div class="modal-header">

                <h5 class="modal-title fw-bold">
                    Bukti Kegiatan
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>

            <div class="modal-body text-center p-4">

                <div id="loadingBukti" class="py-5">

                    <div
                        class="spinner-border text-primary"
                        role="status"
                    ></div>

                    <div class="mt-2 text-muted">
                        Memuat bukti kegiatan...
                    </div>

                </div>

                <img
                    id="gambarBukti"
                    src=""
                    alt="Bukti kegiatan"
                    class="img-fluid rounded border d-none"
                    style="
                        max-height: 650px;
                        max-width: 100%;
                        object-fit: contain;
                    "
                >

                <div
                    id="errorBukti"
                    class="alert alert-danger d-none mt-3"
                >
                    Bukti kegiatan tidak dapat ditampilkan.
                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('js')

<script>

let tableLogbook;


/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

function statusBadge(status)
{
    const normalized = String(
        status || 'Menunggu'
    ).trim().toLowerCase();

    if (
        normalized === 'disetujui' ||
        normalized === 'approved'
    ) {

        return `
            <span class="badge bg-success">
                Disetujui
            </span>
        `;

    }

    return `
        <span class="badge bg-warning text-dark">
            Menunggu
        </span>
    `;
}


/*
|--------------------------------------------------------------------------
| KETERANGAN AKSI
|--------------------------------------------------------------------------
|
| Admin hanya melihat keterangan approval.
| Tidak ada tombol edit, hapus, atau approve.
|--------------------------------------------------------------------------
*/

function approvalInfo(status)
{
    const normalized = String(
        status || 'Menunggu'
    ).trim().toLowerCase();

    if (
        normalized === 'disetujui' ||
        normalized === 'approved'
    ) {

        return `
            <span class="text-success">
                Sudah di-approve
            </span>
        `;

    }

    return `
        <span class="text-muted">
            Belum di-approve
        </span>
    `;
}


/*
|--------------------------------------------------------------------------
| DOCUMENT READY
|--------------------------------------------------------------------------
*/

$(document).ready(function () {

tableLogbook = $('#logbookTable').DataTable({

    processing: true,

    responsive: false,

    autoWidth: false,

    ajax: {
        url: '/back-office/logbook/data',

        type: 'GET',

        dataSrc: function (response) {
            if (
                !response ||
                response.status !== 'success'
            ) {
                return [];
            }

            return response.data || [];
        }
    },

    columns: [

        {
            data: null,
            className: 'text-center',
            orderable: false,
            searchable: false,
            render: function () {
                return '';
            }
        },

        {
            data: 'tanggal',
            defaultContent: '-'
        },

        {
            data: 'nama_peserta',
            defaultContent: '-'
        },

        {
            data: 'mentor',
            defaultContent: '-'
        },

        {
            data: 'aktivitas',
            defaultContent: '-'
        },

        {
            data: 'hasil',
            defaultContent: '-'
        },

        {
            data: 'catatan',
            defaultContent: '-',
            render: function (data) {
                return data
                    ? escapeHtml(data)
                    : '-';
            }
        },

        {
            data: null,
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data) {

                if (!data || !data.bukti_url) {
                    return `
                        <span class="text-muted">
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
                        Lihat
                    </button>
                `;
            }
        },

        {
            data: 'status',
            className: 'text-center',

            render: function (data) {
                return statusBadge(data);
            }
        },

        {
            data: 'catatan_mentor',
            defaultContent: '-',

            render: function (data) {

                if (!data) {
                    return `
                        <span class="text-muted">
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

    /*
    |--------------------------------------------------------------------------
    | NOMOR URUT
    |--------------------------------------------------------------------------
    */

    rowCallback: function (row, data, displayIndex) {

        const pageInfo = this.api().page.info();

        const nomor =
            pageInfo.start +
            displayIndex +
            1;

        $('td:eq(0)', row).html(nomor);
    },

    /*
    |--------------------------------------------------------------------------
    | URUTKAN BERDASARKAN TANGGAL
    |--------------------------------------------------------------------------
    */

    order: [
        [1, 'desc']
    ],

    language: {

        processing: 'Memuat data...',

        search: 'Cari:',

        lengthMenu:
            'Tampilkan _MENU_ data',

        info:
            'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',

        infoEmpty:
            'Tidak ada data',

        zeroRecords:
            'Data logbook tidak ditemukan',

        emptyTable:
            'Belum ada data logbook',

        paginate: {
            first: 'Pertama',
            last: 'Terakhir',
            next: '›',
            previous: '‹'
        }

    }

});
});


/*
|--------------------------------------------------------------------------
| LIHAT BUKTI KEGIATAN
|--------------------------------------------------------------------------
*/

$(document).on(
    'click',
    '.btn-bukti',
    function ()
{

    const url = $(this).attr('data-url');

    if (!url) {

        Swal.fire({

            icon: 'warning',

            title: 'Bukti Tidak Tersedia',

            text:
                'File bukti kegiatan tidak ditemukan.'

        });

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | Reset modal
    |--------------------------------------------------------------------------
    */

    $('#gambarBukti')
        .addClass('d-none')
        .attr('src', '');

    $('#loadingBukti')
        .removeClass('d-none');

    $('#errorBukti')
        .addClass('d-none');


    /*
    |--------------------------------------------------------------------------
    | Buka modal
    |--------------------------------------------------------------------------
    */

    const modalElement =
        document.getElementById('modalBukti');

    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );

    modal.show();


    /*
    |--------------------------------------------------------------------------
    | Cek gambar terlebih dahulu
    |--------------------------------------------------------------------------
    */

    const image = new Image();

    image.onload = function () {

        $('#loadingBukti')
            .addClass('d-none');

        $('#errorBukti')
            .addClass('d-none');

        $('#gambarBukti')
            .attr('src', url)
            .removeClass('d-none');

    };


    image.onerror = function () {

        $('#loadingBukti')
            .addClass('d-none');

        $('#gambarBukti')
            .addClass('d-none');

        $('#errorBukti')
            .removeClass('d-none');

        console.error(
            'Bukti kegiatan gagal dimuat:',
            url
        );

    };


    image.src = url;

});


/*
|--------------------------------------------------------------------------
| ESCAPE HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(value)
{
    return $('<div>')
        .text(value || '')
        .html();
}


/*
|--------------------------------------------------------------------------
| ESCAPE ATTRIBUTE
|--------------------------------------------------------------------------
*/

function escapeAttribute(value)
{
    return String(value || '')
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

</script>

@endpush
