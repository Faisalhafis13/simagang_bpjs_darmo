@extends('layouts.back-office')

@section('title', 'Logbook Saya')

@section('content')

<div class="container-fluid">

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
            class="btn btn-primary"
            id="btnTambah"
        >
            <i class="bi bi-plus-circle"></i>
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
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Aktivitas</th>
                            <th>Hasil</th>
                            <th>Catatan</th>
                            <th>Bukti</th>
                            <th>Status</th>
                            <th>Catatan Mentor</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- MODAL LOGBOOK --}}
<div
    class="modal fade"
    id="modalLogbook"
    tabindex="-1"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content border-0 rounded-4">

            <form id="formLogbook">

                @csrf

                <input
                    type="hidden"
                    id="id"
                >

                <div class="modal-header">

                    <h5 class="modal-title">
                        Logbook
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Tanggal
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            id="tanggal"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Aktivitas
                        </label>

                        <textarea
                            id="aktivitas"
                            class="form-control"
                            rows="4"
                            required
                        ></textarea>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Hasil
                        </label>

                        <textarea
                            id="hasil"
                            class="form-control"
                            rows="4"
                            required
                        ></textarea>

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Catatan
                        </label>

                        <textarea
                            id="catatan"
                            class="form-control"
                            rows="3"
                        ></textarea>

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Bukti Kegiatan
                        </label>

                        <input
                            type="file"
                            class="form-control"
                            id="bukti"
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

                        <strong>
                            Feedback Mentor:
                        </strong>

                        <div
                            id="isiFeedbackMentor"
                            class="mt-1"
                        ></div>

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

let modalLogbook;

let tableLogbook;


/*
|--------------------------------------------------------------------------
| STATUS BADGE
|--------------------------------------------------------------------------
*/

function statusBadge(status)
{
    const normalized =
        String(status || 'Menunggu').toLowerCase();

    if (
        normalized === 'disetujui' ||
        normalized === 'approved'
    ) {

        return `
            <span class="badge bg-success">
                <i class="bi bi-check-circle"></i>
                Disetujui
            </span>
        `;

    }

    return `
        <span class="badge bg-warning text-dark">
            <i class="bi bi-clock"></i>
            Menunggu
        </span>
    `;
}


/*
|--------------------------------------------------------------------------
| AKSI EDIT & HAPUS
|--------------------------------------------------------------------------
|
| Menunggu:
|   Edit aktif
|   Hapus aktif
|
| Disetujui:
|   Edit disabled
|   Hapus disabled
|
| Data lainnya tetap tampil.
|
*/

function actionButtons(data)
{
    const approved =
        String(data.status || '')
            .toLowerCase() === 'disetujui';


    if (approved) {

        return `
            <div class="d-flex gap-1">

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
        <div class="d-flex gap-1">

            <button
                type="button"
                class="btn btn-sm btn-warning btn-edit"
                data-id="${data.id}"
                title="Edit logbook"
            >
                <i class="bi bi-pencil-square"></i>
                Edit
            </button>

            <button
                type="button"
                class="btn btn-sm btn-danger btn-delete"
                data-id="${data.id}"
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
| DOCUMENT READY
|--------------------------------------------------------------------------
*/

$(function () {

    modalLogbook = new bootstrap.Modal(
        document.getElementById('modalLogbook')
    );


    tableLogbook = $('#tableLogbook').DataTable({

        processing: true,

        ajax: {

            url: '/peserta/logbook/data',

            dataSrc: function (response) {

                return response.data || [];

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
            | TANGGAL
            |--------------------------------------------------------------------------
            */

            {
                data: 'tanggal',

                render: function (data) {

                    return data || '-';

                }

            },


            /*
            |--------------------------------------------------------------------------
            | AKTIVITAS
            |--------------------------------------------------------------------------
            */

            {
                data: 'aktivitas',

                render: function (data) {

                    return data || '-';

                }

            },


            /*
            |--------------------------------------------------------------------------
            | HASIL
            |--------------------------------------------------------------------------
            */

            {
                data: 'hasil',

                render: function (data) {

                    return data || '-';

                }

            },


            /*
            |--------------------------------------------------------------------------
            | CATATAN PESERTA
            |--------------------------------------------------------------------------
            */

            {
                data: 'catatan',

                render: function (data) {

                    return data || '-';

                }

            },


            /*
            |--------------------------------------------------------------------------
            | BUKTI
            |--------------------------------------------------------------------------
            */

            {
                data: null,

                orderable: false,

                searchable: false,

                render: function (data) {

                    if (!data.bukti_url) {

                        return `
                            <span class="text-muted">
                                Tidak ada bukti
                            </span>
                        `;

                    }


                    return `
                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary btn-bukti"
                            data-url="${data.bukti_url}"
                        >
                            <i class="bi bi-image"></i>
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

                render: function (data) {

                    if (!data) {

                        return `
                            <span class="text-muted">
                                Belum ada feedback
                            </span>
                        `;

                    }


                    return `
                        <div class="text-start">
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

                orderable: false,

                searchable: false,

                render: function (data) {

                    return actionButtons(data);

                }

            }

        ]

    });

});


/*
|--------------------------------------------------------------------------
| TAMBAH LOGBOOK
|--------------------------------------------------------------------------
*/

$('#btnTambah').on('click', function () {

    $('#formLogbook')[0].reset();

    $('#id').val('');

    $('#previewBukti').html('');

    $('#feedbackMentor')
        .addClass('d-none');

    $('#isiFeedbackMentor')
        .html('');

    $('#btnSimpan')
        .prop('disabled', false)
        .text('Simpan');

    modalLogbook.show();

});


/*
|--------------------------------------------------------------------------
| SUBMIT FORM
|--------------------------------------------------------------------------
*/

$('#formLogbook').on(
    'submit',
    async function (event)
{

    event.preventDefault();


    const id = $('#id').val();


    const url = id
        ? `/peserta/logbook/${id}`
        : '/peserta/logbook';


    const formData = new FormData();


    formData.append(
        '_token',
        $('meta[name="csrf-token"]').attr('content')
    );


    if (id) {

        formData.append(
            '_method',
            'PUT'
        );

    }


    formData.append(
        'tanggal',
        $('#tanggal').val()
    );


    formData.append(
        'aktivitas',
        $('#aktivitas').val()
    );


    formData.append(
        'hasil',
        $('#hasil').val()
    );


    formData.append(
        'catatan',
        $('#catatan').val()
    );


    const file =
        $('#bukti')[0].files[0];


    if (file) {

        formData.append(
            'bukti',
            file
        );

    }


    $('#btnSimpan')
        .prop('disabled', true)
        .text('Menyimpan...');


    try {

        const response = await fetch(
            url,
            {
                method: 'POST',
                body: formData
            }
        );


        const result =
            await response.json();


        if (!response.ok) {

            throw new Error(
                result.message ||
                'Terjadi kesalahan.'
            );

        }


        modalLogbook.hide();


        tableLogbook
            .ajax
            .reload(null, false);


        Swal.fire({

            icon: 'success',

            title: 'Berhasil',

            text: result.message,

            timer: 1500,

            showConfirmButton: false

        });


    } catch (error) {

        Swal.fire({

            icon: 'error',

            title: 'Gagal',

            text: error.message

        });


    } finally {

        $('#btnSimpan')
            .prop('disabled', false)
            .text('Simpan');

    }

});


/*
|--------------------------------------------------------------------------
| EDIT LOGBOOK
|--------------------------------------------------------------------------
*/

$(document).on(
    'click',
    '.btn-edit',
    async function ()
{

    const id =
        $(this).data('id');


    try {

        const response =
            await fetch(
                `/peserta/logbook/${id}`
            );


        const result =
            await response.json();


        if (!response.ok) {

            throw new Error(
                result.message ||
                'Gagal mengambil data logbook.'
            );

        }


        const data =
            result.data;


        /*
        |--------------------------------------------------------------------------
        | PENGAMAN FRONTEND
        |--------------------------------------------------------------------------
        |
        | Tombol sudah disabled untuk Disetujui,
        | tapi kita tetap cek status lagi di sini.
        |
        */

        if (
            String(data.status || '')
                .toLowerCase() === 'disetujui'
        ) {

            Swal.fire({

                icon: 'info',

                title: 'Logbook Sudah Disetujui',

                text: 'Logbook yang sudah disetujui mentor tidak dapat diedit lagi.',

                confirmButtonText: 'Mengerti'

            });

            return;

        }


        $('#id').val(data.id);

        $('#tanggal').val(data.tanggal);

        $('#aktivitas').val(data.aktivitas);

        $('#hasil').val(data.hasil);

        $('#catatan').val(
            data.catatan || ''
        );

        $('#bukti').val('');


        /*
        |--------------------------------------------------------------------------
        | PREVIEW BUKTI
        |--------------------------------------------------------------------------
        */

        if (data.bukti_url) {

            $('#previewBukti').html(`

                <div>

                    <p class="mb-2 fw-bold">
                        Bukti saat ini:
                    </p>

                    <img
                        src="${data.bukti_url}"
                        alt="Bukti kegiatan"
                        class="img-fluid rounded border"
                        style="max-height:250px;"
                    >

                    <div class="small text-muted mt-2">
                        Upload file baru jika ingin mengganti bukti.
                    </div>

                </div>

            `);

        } else {

            $('#previewBukti').html(`

                <div class="text-muted">
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
                .html('');

        }


        modalLogbook.show();


    } catch (error) {

        Swal.fire({

            icon: 'error',

            title: 'Gagal',

            text: error.message

        });

    }

});


/*
|--------------------------------------------------------------------------
| HAPUS LOGBOOK
|--------------------------------------------------------------------------
*/

$(document).on(
    'click',
    '.btn-delete',
    async function ()
{

    const id =
        $(this).data('id');


    try {

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS TERLEBIH DAHULU
        |--------------------------------------------------------------------------
        */

        const detailResponse =
            await fetch(
                `/peserta/logbook/${id}`
            );


        const detailResult =
            await detailResponse.json();


        if (!detailResponse.ok) {

            throw new Error(
                detailResult.message ||
                'Gagal mengambil data logbook.'
            );

        }


        const data =
            detailResult.data;


        /*
        |--------------------------------------------------------------------------
        | JIKA SUDAH DISETUJUI
        |--------------------------------------------------------------------------
        */

        if (
            String(data.status || '')
                .toLowerCase() === 'disetujui'
        ) {

            Swal.fire({

                icon: 'info',

                title: 'Logbook Sudah Disetujui',

                text: 'Logbook yang sudah disetujui mentor tidak dapat dihapus lagi.',

                confirmButtonText: 'Mengerti'

            });

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | KONFIRMASI HAPUS
        |--------------------------------------------------------------------------
        */

        const confirm =
            await Swal.fire({

                icon: 'warning',

                title: 'Hapus logbook?',

                text: 'Data logbook akan dihapus dan tidak dapat dikembalikan.',

                showCancelButton: true,

                confirmButtonText: 'Ya, hapus',

                cancelButtonText: 'Batal'

            });


        if (!confirm.isConfirmed) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        */

        const response =
            await fetch(
                `/peserta/logbook/${id}`,
                {
                    method: 'DELETE',

                    headers: {

                        'X-CSRF-TOKEN':
                            $('meta[name="csrf-token"]')
                                .attr('content')

                    }

                }
            );


        const result =
            await response.json();


        if (!response.ok) {

            throw new Error(
                result.message ||
                'Gagal menghapus logbook.'
            );

        }


        tableLogbook
            .ajax
            .reload(null, false);


        Swal.fire({

            icon: 'success',

            title: 'Berhasil',

            text: result.message,

            timer: 1200,

            showConfirmButton: false

        });


    } catch (error) {

        Swal.fire({

            icon: 'error',

            title: 'Gagal',

            text: error.message

        });

    }

});


/*
|--------------------------------------------------------------------------
| LIHAT BUKTI
|--------------------------------------------------------------------------
*/

$(document).on(
    'click',
    '.btn-bukti',
    function ()
{

    const url =
        $(this).data('url');


    Swal.fire({

        title: 'Bukti Kegiatan',

        imageUrl: url,

        imageAlt: 'Bukti kegiatan',

        width: '800px',

        showCloseButton: true,

        showConfirmButton: false

    });

});


/*
|--------------------------------------------------------------------------
| ESCAPE HTML
|--------------------------------------------------------------------------
|
| Supaya catatan mentor yang ditampilkan
| sebagai HTML tidak berbahaya.
|
*/

function escapeHtml(text)
{
    return String(text || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

</script>

@endpush
