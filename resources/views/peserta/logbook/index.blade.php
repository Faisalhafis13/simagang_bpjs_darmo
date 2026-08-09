@extends('layouts.back-office')

@section('title', 'Logbook Saya')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
    <h3 class="fw-bold mb-1">
        Logbook Saya
    </h3>

    <small class="text-muted">
        Kelola logbook kegiatan magang Anda.
    </small>
</div>

<button
    type="button"
    class="btn btn-primary"
    id="btnTambah"
>
    <i class="bi bi-plus-circle me-1"></i>
    Tambah Logbook
</button>

</div>

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body">

    <div class="table-responsive">

        <table
            class="table table-bordered table-hover align-middle w-100"
            id="tableLogbook"
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
                        Aktivitas
                    </th>

                    <th>
                        Hasil
                    </th>

                    <th>
                        Catatan
                    </th>

                    <th>
                        Bukti
                    </th>

                    <th class="text-center">
                        Status
                    </th>

                    <th>
                        Catatan Mentor
                    </th>

                    <th width="16%" class="text-center">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody></tbody>

        </table>

    </div>

</div>

</div>

{{-- =========================================================
MODAL LOGBOOK
========================================================= --}}

<div
    class="modal fade"
    id="modalLogbook"
    tabindex="-1"
    aria-labelledby="modalLogbookLabel"
    aria-hidden="true"
>

<div class="modal-dialog modal-lg modal-dialog-centered">

    <div class="modal-content border-0 rounded-4 shadow">

        <form id="formLogbook">

            @csrf

            <input
                type="hidden"
                id="id"
                name="id"
            >

            <div class="modal-header">

                <h5
                    class="modal-title fw-bold"
                    id="modalLogbookLabel"
                >
                    Logbook
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <div class="modal-body">

                <div class="mb-3">

                    <label
                        for="tanggal"
                        class="form-label fw-semibold"
                    >
                        Tanggal
                    </label>

                    <input
                        type="date"
                        class="form-control"
                        id="tanggal"
                        name="tanggal"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label
                        for="aktivitas"
                        class="form-label fw-semibold"
                    >
                        Aktivitas
                    </label>

                    <textarea
                        id="aktivitas"
                        name="aktivitas"
                        class="form-control"
                        rows="4"
                        placeholder="Jelaskan aktivitas yang dilakukan..."
                        required
                    ></textarea>

                </div>


                <div class="mb-3">

                    <label
                        for="hasil"
                        class="form-label fw-semibold"
                    >
                        Hasil
                    </label>

                    <textarea
                        id="hasil"
                        name="hasil"
                        class="form-control"
                        rows="4"
                        placeholder="Jelaskan hasil dari aktivitas..."
                        required
                    ></textarea>

                </div>


                <div class="mb-3">

                    <label
                        for="catatan"
                        class="form-label fw-semibold"
                    >
                        Catatan
                    </label>

                    <textarea
                        id="catatan"
                        name="catatan"
                        class="form-control"
                        rows="3"
                        placeholder="Tambahkan catatan jika diperlukan..."
                    ></textarea>

                </div>


                <div class="mb-3">

                    <label
                        for="bukti"
                        class="form-label fw-bold"
                    >
                        Bukti Kegiatan
                    </label>

                    <input
                        type="file"
                        class="form-control"
                        id="bukti"
                        name="bukti"
                        accept="image/jpeg,image/png,image/webp"
                    >

                    <small class="text-muted">
                        Upload foto atau screenshot sebagai bukti kegiatan.
                        Maksimal 5 MB.
                    </small>

                </div>


                <div
                    id="previewBukti"
                    class="mt-3"
                ></div>


                {{-- FEEDBACK MENTOR --}}

                <div
                    id="feedbackMentor"
                    class="alert alert-info d-none mt-3"
                >

                    <div class="d-flex">

                        <i class="bi bi-chat-left-text me-2"></i>

                        <div>

                            <strong>
                                Feedback Mentor
                            </strong>

                            <div
                                id="isiFeedbackMentor"
                                class="mt-1"
                            ></div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                    id="btnSimpan"
                >
                    <i class="bi bi-save me-1"></i>
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

</div>

@endsection

@push('js')

<script>

let modalLogbook = null;
let tableLogbook = null;


/*
|--------------------------------------------------------------------------
| CSRF TOKEN
|--------------------------------------------------------------------------
*/

function getCsrfToken()
{
    return $('meta[name="csrf-token"]').attr('content')
        || $('#formLogbook input[name="_token"]').val()
        || '';
}


/*
|--------------------------------------------------------------------------
| ESCAPE HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(value)
{
    return $('<div>')
        .text(value ?? '')
        .html();
}


/*
|--------------------------------------------------------------------------
| ESCAPE ATTRIBUTE
|--------------------------------------------------------------------------
*/

function escapeAttribute(value)
{
    return escapeHtml(value);
}


/*
|--------------------------------------------------------------------------
| NORMALIZE STATUS
|--------------------------------------------------------------------------
*/

function normalizeStatus(status)
{
    return String(status || 'Menunggu')
        .trim()
        .toLowerCase();
}


/*
|--------------------------------------------------------------------------
| CHECK APPROVED
|--------------------------------------------------------------------------
*/

function isApproved(status)
{
    const normalized = normalizeStatus(status);

    return [
        'disetujui',
        'approved'
    ].includes(normalized);
}


/*
|--------------------------------------------------------------------------
| STATUS BADGE
|--------------------------------------------------------------------------
*/

function statusBadge(status)
{
    if (isApproved(status)) {

        return `
            <span class="badge bg-success">
                <i class="bi bi-check-circle me-1"></i>
                Disetujui
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


/*
|--------------------------------------------------------------------------
| ACTION BUTTONS
|--------------------------------------------------------------------------
*/

function actionButtons(data)
{
    const id = escapeAttribute(data.id);

    if (isApproved(data.status)) {

        return `
            <div class="d-flex justify-content-center gap-1">

                <button
                    type="button"
                    class="btn btn-sm btn-warning"
                    disabled
                    title="Logbook sudah disetujui mentor"
                >
                    <i class="bi bi-pencil-square"></i>
                    Edit
                </button>

                <button
                    type="button"
                    class="btn btn-sm btn-danger"
                    disabled
                    title="Logbook sudah disetujui mentor"
                >
                    <i class="bi bi-trash"></i>
                    Hapus
                </button>

            </div>
        `;
    }

    return `
        <div class="d-flex justify-content-center gap-1">

            <button
                type="button"
                class="btn btn-sm btn-warning btn-edit"
                data-id="${id}"
                title="Edit logbook"
            >
                <i class="bi bi-pencil-square"></i>
                Edit
            </button>

            <button
                type="button"
                class="btn btn-sm btn-danger btn-delete"
                data-id="${id}"
                title="Hapus logbook"
            >
                <i class="bi bi-trash"></i>
                Hapus
            </button>

        </div>
    `;
}


/*
|--------------------------------------------------------------------------
| API JSON HELPER
|--------------------------------------------------------------------------
*/

async function fetchJson(url, options = {})
{
    const response = await fetch(url, {
        ...options,

        headers: {
            'Accept': 'application/json',
            ...(options.headers || {})
        }
    });

    let result = null;

    try {
        result = await response.json();
    } catch (error) {
        result = null;
    }

    if (!response.ok) {

        throw new Error(
            result?.message ||
            'Terjadi kesalahan pada server.'
        );
    }

    return result;
}


/*
|--------------------------------------------------------------------------
| RESET MODAL
|--------------------------------------------------------------------------
*/

function resetModal()
{
    $('#formLogbook')[0].reset();

    $('#id').val('');

    $('#previewBukti').empty();

    $('#feedbackMentor')
        .addClass('d-none');

    $('#isiFeedbackMentor')
        .empty();

    $('#btnSimpan')
        .prop('disabled', false)
        .html(`
            <i class="bi bi-save me-1"></i>
            Simpan
        `);

    $('#modalLogbookLabel')
        .text('Tambah Logbook');
}


/*
|--------------------------------------------------------------------------
| DOCUMENT READY
|--------------------------------------------------------------------------
*/

$(function () {

    const modalElement =
        document.getElementById('modalLogbook');

    if (modalElement) {

        modalLogbook =
            bootstrap.Modal.getOrCreateInstance(
                modalElement
            );

    }


    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    tableLogbook = $('#tableLogbook').DataTable({

        processing: true,

        responsive: false,

        autoWidth: false,

        pageLength: 10,

        order: [
            [1, 'desc']
        ],

        ajax: {

            url: '/peserta/logbook/data',

            type: 'GET',

            dataSrc: function (response) {

                if (
                    !response ||
                    response.status !== 'success'
                ) {

                    return response?.data || [];

                }

                return response.data || [];

            },

            error: function (xhr) {

                console.error(
                    'Gagal memuat logbook:',
                    xhr.responseText
                );

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

                className: 'text-center',

                orderable: false,

                searchable: false,

                render: function (
                    data,
                    type,
                    row,
                    meta
                ) {

                    return (
                        meta.settings._iDisplayStart +
                        meta.row +
                        1
                    );

                }

            },


            /*
            |--------------------------------------------------------------------------
            | TANGGAL
            |--------------------------------------------------------------------------
            */

            {
                data: 'tanggal',

                defaultContent: '-',

                render: function (data) {

                    return escapeHtml(
                        data || '-'
                    );

                }

            },


            /*
            |--------------------------------------------------------------------------
            | AKTIVITAS
            |--------------------------------------------------------------------------
            */

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
                                min-width: 200px;
                                max-width: 350px;
                                white-space: normal;
                                word-break: break-word;
                            "
                        >
                            ${escapeHtml(data)}
                        </div>
                    `;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | HASIL
            |--------------------------------------------------------------------------
            */

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
                                min-width: 200px;
                                max-width: 350px;
                                white-space: normal;
                                word-break: break-word;
                            "
                        >
                            ${escapeHtml(data)}
                        </div>
                    `;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | CATATAN PESERTA
            |--------------------------------------------------------------------------
            */

            {
                data: 'catatan',

                defaultContent: '-',

                render: function (data) {

                    if (!data) {
                        return '-';
                    }

                    return `
                        <div
                            style="
                                min-width: 180px;
                                max-width: 300px;
                                white-space: normal;
                                word-break: break-word;
                            "
                        >
                            ${escapeHtml(data)}
                        </div>
                    `;

                }

            },


            /*
            |--------------------------------------------------------------------------
            | BUKTI
            |--------------------------------------------------------------------------
            */

            {
                data: null,

                className: 'text-center',

                orderable: false,

                searchable: false,

                render: function (data) {

                    if (
                        !data ||
                        !data.bukti_url
                    ) {

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
                            <i class="bi bi-image me-1"></i>
                            Lihat
                        </button>
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

                className: 'text-center',

                render: function (data) {

                    return statusBadge(data);

                }

            },


            /*
            |--------------------------------------------------------------------------
            | CATATAN MENTOR
            |--------------------------------------------------------------------------
            */

            {
                data: 'catatan_mentor',

                defaultContent: '-',

                render: function (data) {

                    if (!data) {

                        return `
                            <span class="text-muted">
                                Belum ada feedback
                            </span>
                        `;

                    }

                    return `
                        <div
                            style="
                                min-width: 180px;
                                max-width: 300px;
                                white-space: normal;
                                word-break: break-word;
                            "
                        >
                            ${escapeHtml(data)}
                        </div>
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

                className: 'text-center',

                orderable: false,

                searchable: false,

                render: function (data) {

                    return actionButtons(data);

                }

            }

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


    /*
    |--------------------------------------------------------------------------
    | TAMBAH LOGBOOK
    |--------------------------------------------------------------------------
    */

    $('#btnTambah').on('click', function () {

        resetModal();

        modalLogbook.show();

    });


    /*
    |--------------------------------------------------------------------------
    | RESET SAAT MODAL DITUTUP
    |--------------------------------------------------------------------------
    */

    $('#modalLogbook').on(
        'hidden.bs.modal',
        function () {

            resetModal();

        }
    );

});


/*
|--------------------------------------------------------------------------
| SUBMIT FORM
|--------------------------------------------------------------------------
*/

$('#formLogbook').on(
    'submit',
    async function (event) {

        event.preventDefault();

        const id = $('#id').val();

        const url = id
            ? `/peserta/logbook/${encodeURIComponent(id)}`
            : '/peserta/logbook';

        const formData =
            new FormData(this);

        if (id) {

            formData.append(
                '_method',
                'PUT'
            );

        }

        const file =
            $('#bukti')[0]?.files?.[0];

        if (file) {

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            const maxSize =
                5 * 1024 * 1024;

            if (
                !allowedTypes.includes(
                    file.type
                )
            ) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Format File Tidak Valid',
                    text: 'Bukti harus berupa JPG, PNG, atau WEBP.'
                });

                return;

            }

            if (file.size > maxSize) {

                Swal.fire({
                    icon: 'warning',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran bukti maksimal 5 MB.'
                });

                return;

            }

        }

        const button =
            $('#btnSimpan');

        button
            .prop('disabled', true)
            .html(`
                <span
                    class="spinner-border spinner-border-sm me-1"
                    role="status"
                ></span>
                Menyimpan...
            `);


        try {

            const result =
                await fetchJson(
                    url,
                    {
                        method: 'POST',

                        headers: {
                            'X-CSRF-TOKEN':
                                getCsrfToken()
                        },

                        body: formData
                    }
                );


            if (modalLogbook) {
                modalLogbook.hide();
            }


            tableLogbook
                .ajax
                .reload(null, false);


            await Swal.fire({

                icon: 'success',

                title: 'Berhasil',

                text:
                    result.message ||
                    'Logbook berhasil disimpan.',

                timer: 1500,

                showConfirmButton: false

            });


        } catch (error) {

            console.error(error);

            Swal.fire({

                icon: 'error',

                title: 'Gagal',

                text: error.message

            });

        } finally {

            button
                .prop('disabled', false)
                .html(`
                    <i class="bi bi-save me-1"></i>
                    Simpan
                `);

        }

    }
);


/*
|--------------------------------------------------------------------------
| EDIT LOGBOOK
|--------------------------------------------------------------------------
*/

$(document).on(
    'click',
    '.btn-edit',
    async function () {

        const id =
            $(this).data('id');

        if (!id) {
            return;
        }

        try {

            const result =
                await fetchJson(
                    `/peserta/logbook/${encodeURIComponent(id)}`
                );

            const data =
                result.data || result;


            /*
            |--------------------------------------------------------------------------
            | CEK STATUS
            |--------------------------------------------------------------------------
            */

            if (isApproved(data.status)) {

                Swal.fire({

                    icon: 'info',

                    title:
                        'Logbook Sudah Disetujui',

                    text:
                        'Logbook yang sudah disetujui mentor tidak dapat diedit lagi.',

                    confirmButtonText:
                        'Mengerti'

                });

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | ISI FORM
            |--------------------------------------------------------------------------
            */

            $('#id').val(data.id || '');

            $('#tanggal')
                .val(data.tanggal || '');

            $('#aktivitas')
                .val(data.aktivitas || '');

            $('#hasil')
                .val(data.hasil || '');

            $('#catatan')
                .val(data.catatan || '');

            $('#bukti')
                .val('');


            $('#modalLogbookLabel')
                .text('Edit Logbook');


            /*
            |--------------------------------------------------------------------------
            | PREVIEW BUKTI
            |--------------------------------------------------------------------------
            */

            if (data.bukti_url) {

                $('#previewBukti').html(`

                    <div class="border rounded-3 p-3">

                        <div class="fw-semibold mb-2">
                            <i class="bi bi-image me-1"></i>
                            Bukti saat ini
                        </div>

                        <div class="text-center">

                            <img
                                src="${escapeAttribute(data.bukti_url)}"
                                alt="Bukti kegiatan"
                                class="img-fluid rounded border"
                                style="
                                    max-height:250px;
                                    object-fit:contain;
                                "
                                onerror="
                                    this.style.display='none';
                                    this.nextElementSibling.classList.remove('d-none');
                                "
                            >

                            <div
                                class="text-danger d-none mt-2"
                            >
                                Bukti tidak dapat ditampilkan.
                            </div>

                        </div>

                        <div class="small text-muted mt-2">
                            Upload file baru jika ingin mengganti bukti.
                        </div>

                    </div>

                `);

            } else {

                $('#previewBukti').html(`

                    <div class="alert alert-light border mb-0">

                        <i class="bi bi-info-circle me-1"></i>

                        Belum ada bukti kegiatan.

                    </div>

                `);

            }


            /*
            |--------------------------------------------------------------------------
            | FEEDBACK MENTOR
            |--------------------------------------------------------------------------
            */

            if (data.catatan_mentor) {

                $('#feedbackMentor')
                    .removeClass('d-none');

                $('#isiFeedbackMentor')
                    .text(data.catatan_mentor);

            } else {

                $('#feedbackMentor')
                    .addClass('d-none');

                $('#isiFeedbackMentor')
                    .empty();

            }


            modalLogbook.show();


        } catch (error) {

            console.error(error);

            Swal.fire({

                icon: 'error',

                title: 'Gagal',

                text: error.message

            });

        }

    }
);


/*
|--------------------------------------------------------------------------
| HAPUS LOGBOOK
|--------------------------------------------------------------------------
*/

$(document).on(
    'click',
    '.btn-delete',
    async function () {

        const id =
            $(this).data('id');

        if (!id) {
            return;
        }


        try {

            /*
            |--------------------------------------------------------------------------
            | AMBIL DETAIL TERLEBIH DAHULU
            |--------------------------------------------------------------------------
            */

            const detailResult =
                await fetchJson(
                    `/peserta/logbook/${encodeURIComponent(id)}`
                );

            const data =
                detailResult.data ||
                detailResult;


            /*
            |--------------------------------------------------------------------------
            | CEK STATUS
            |--------------------------------------------------------------------------
            */

            if (isApproved(data.status)) {

                Swal.fire({

                    icon: 'info',

                    title:
                        'Logbook Sudah Disetujui',

                    text:
                        'Logbook yang sudah disetujui mentor tidak dapat dihapus lagi.',

                    confirmButtonText:
                        'Mengerti'

                });

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | KONFIRMASI
            |--------------------------------------------------------------------------
            */

            const confirmation =
                await Swal.fire({

                    icon: 'warning',

                    title:
                        'Hapus logbook?',

                    text:
                        'Data logbook akan dihapus dan tidak dapat dikembalikan.',

                    showCancelButton: true,

                    confirmButtonText:
                        'Ya, hapus',

                    cancelButtonText:
                        'Batal',

                    reverseButtons: true

                });


            if (!confirmation.isConfirmed) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            const result =
                await fetchJson(
                    `/peserta/logbook/${encodeURIComponent(id)}`,
                    {

                        method: 'DELETE',

                        headers: {

                            'X-CSRF-TOKEN':
                                getCsrfToken(),

                            'Accept':
                                'application/json'

                        }

                    }
                );


            tableLogbook
                .ajax
                .reload(null, false);


            Swal.fire({

                icon: 'success',

                title: 'Berhasil',

                text:
                    result.message ||
                    'Logbook berhasil dihapus.',

                timer: 1200,

                showConfirmButton: false

            });


        } catch (error) {

            console.error(error);

            Swal.fire({

                icon: 'error',

                title: 'Gagal',

                text: error.message

            });

        }

    }
);


/*
|--------------------------------------------------------------------------
| LIHAT BUKTI
|--------------------------------------------------------------------------
*/

$(document).on(
    'click',
    '.btn-bukti',
    function () {

        const url =
            $(this).attr('data-url');

        if (!url) {

            Swal.fire({

                icon: 'warning',

                title:
                    'Bukti Tidak Tersedia',

                text:
                    'File bukti kegiatan tidak ditemukan.'

            });

            return;

        }


        Swal.fire({

            title:
                'Bukti Kegiatan',

            imageUrl:
                url,

            imageAlt:
                'Bukti kegiatan',

            width:
                '800px',

            showCloseButton:
                true,

            showConfirmButton:
                false,

            imageClass:
                'img-fluid rounded'

        });

    }
);

</script>

@endpush
