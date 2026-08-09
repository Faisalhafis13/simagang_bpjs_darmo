@extends('layouts.back-office')

@section('title', 'Data Peserta')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Data Peserta
        </h3>

        <small class="text-muted">
            Daftar kelompok peserta yang diterima beserta surat penerimaannya.
        </small>

    </div>

</div>


<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body">

        <div class="table-responsive">

            <table
                id="tablePeserta"
                class="table table-bordered table-hover align-middle w-100"
            >

                <thead class="table-light">

                    <tr>

                        <th
                            width="5%"
                            class="text-center"
                        >
                            No
                        </th>

                        <th
                            width="15%"
                            class="text-center"
                        >
                            Kode Pengajuan
                        </th>

                        <th
                            width="18%"
                            class="text-center"
                        >
                            Perguruann Tinggi
                        </th>

                        <th>
                            Nama Peserta
                        </th>

                        <th
                            width="12%"
                            class="text-center"
                        >
                            Jumlah
                        </th>

                        <th
                            width="12%"
                            class="text-center"
                        >
                            Status
                        </th>

                        <th
                            width="20%"
                            class="text-center"
                        >
                            Surat Penerimaan
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td
                            colspan="7"
                            class="text-center"
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
{{-- MODAL UPLOAD SURAT --}}
{{-- ========================================================= --}}

<div
    class="modal fade"
    id="modalSuratPenerimaan"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content border-0 shadow rounded-4">

            <form
                id="formSuratPenerimaan"
                enctype="multipart/form-data"
            >

                @csrf

                <input
                    type="hidden"
                    id="pengajuan_id"
                >

                <div class="modal-header">

                    <div>

                        <h5 class="modal-title fw-bold mb-1">
                            Upload Surat Penerimaan
                        </h5>

                        <small class="text-muted">
                            Satu surat berlaku untuk seluruh anggota kelompok.
                        </small>

                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Kode Pengajuan
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="kode_pengajuan_modal"
                            readonly
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            File Surat Penerimaan
                        </label>

                        <input
                            type="file"
                            class="form-control"
                            id="surat_penerimaan"
                            name="surat_penerimaan"
                            accept=".pdf,application/pdf"
                            required
                        >

                        <div class="form-text">
                            Format PDF dengan ukuran maksimal 5 MB.
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
                        id="btnUploadSurat"
                    >
                        <i class="bi bi-upload me-1"></i>
                        Upload Surat
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL HAPUS SURAT --}}
{{-- ========================================================= --}}

<div
    class="modal fade"
    id="modalHapusSurat"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow rounded-4">

            <div class="modal-header">

                <h5 class="modal-title fw-bold">
                    Hapus Surat Penerimaan
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <p class="mb-0">
                    Yakin ingin menghapus surat penerimaan
                    kelompok ini?
                </p>

                <div class="alert alert-warning mt-3 mb-0">

                    <i class="bi bi-exclamation-triangle me-1"></i>

                    Surat ini akan dihapus untuk seluruh anggota
                    kelompok.

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
                    type="button"
                    class="btn btn-danger"
                    id="btnHapusSurat"
                >
                    <i class="bi bi-trash me-1"></i>
                    Hapus Surat
                </button>

            </div>

        </div>

    </div>

</div>

@endsection


@push('js')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */

    const csrfToken =
        $('meta[name="csrf-token"]').attr('content');


    /*
    |--------------------------------------------------------------------------
    | Modal
    |--------------------------------------------------------------------------
    */

    const modalSurat =
        new bootstrap.Modal(
            document.getElementById(
                'modalSuratPenerimaan'
            )
        );


    const modalHapus =
        new bootstrap.Modal(
            document.getElementById(
                'modalHapusSurat'
            )
        );


    /*
    |--------------------------------------------------------------------------
    | ID kelompok yang sedang diproses
    |--------------------------------------------------------------------------
    */

    let selectedPengajuanId = null;


    /*
    |--------------------------------------------------------------------------
    | DataTable
    |--------------------------------------------------------------------------
    */

    const table =
        $('#tablePeserta').DataTable({

            processing: true,

            serverSide: false,

            ajax: {

                url: '/api/back-office/peserta',

                dataSrc: 'data'

            },


            columns: [

                /*
                |--------------------------------------------------------------------------
                | No
                |--------------------------------------------------------------------------
                */

                {
                    data: null,

                    className: 'text-center',

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
                | Kode Pengajuan
                |--------------------------------------------------------------------------
                */

                {
                    data: 'kode_pengajuan',

                    className: 'text-center',

                    render: function (data) {

                        return `
                            <span class="fw-semibold">
                                ${data ?? '-'}
                            </span>
                        `;

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Universitas
                |--------------------------------------------------------------------------
                */

                {
                    data: 'universitas',

                    render: function (data) {

                        return data ?? '-';

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Semua Peserta Dalam Satu Kelompok
                |--------------------------------------------------------------------------
                */

                {
                    data: 'peserta',

                    render: function (peserta) {

                        if (
                            !peserta ||
                            peserta.length === 0
                        ) {

                            return '-';

                        }


                        let html = `
                            <div class="d-flex flex-column gap-2">
                        `;


                        peserta.forEach(function (
                            orang,
                            index
                        ) {

                            const badge =
                                orang.peran === 'Ketua'
                                    ? `
                                        <span class="badge bg-primary ms-1">
                                            Ketua
                                        </span>
                                      `
                                    : `
                                        <span class="badge bg-light text-dark border ms-1">
                                            Anggota
                                        </span>
                                      `;


                            html += `

                                <div>

                                    <div class="fw-semibold">

                                        ${index + 1}.
                                        ${orang.nama ?? '-'}

                                        ${badge}

                                    </div>

                                    <small class="text-muted">

                                        ${orang.email ?? '-'}
                                        ${orang.no_hp
                                            ? ' · ' + orang.no_hp
                                            : ''
                                        }

                                    </small>

                                </div>

                            `;

                        });


                        html += `
                            </div>
                        `;


                        return html;

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Jumlah Peserta
                |--------------------------------------------------------------------------
                */

                {
                    data: 'jumlah_peserta',

                    className: 'text-center',

                    render: function (data) {

                        return `
                            <span class="badge bg-secondary">
                                ${data ?? 0} Orang
                            </span>
                        `;

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                {
                    data: 'status',

                    className: 'text-center',

                    render: function (data) {

                        if (data === 'Diterima') {

                            return `
                                <span class="badge bg-success">
                                    Diterima
                                </span>
                            `;

                        }


                        return `
                            <span class="badge bg-secondary">
                                ${data ?? '-'}
                            </span>
                        `;

                    }

                },


                /*
                |--------------------------------------------------------------------------
                | SATU SURAT UNTUK SATU KELOMPOK
                |--------------------------------------------------------------------------
                */

                {
                    data: null,

                    className: 'text-center',

                    orderable: false,

                    searchable: false,

                    render: function (data) {

                        const id =
                            data.pengajuan_id;


                        /*
                        |--------------------------------------------------------------------------
                        | Belum ada surat
                        |--------------------------------------------------------------------------
                        */

                        if (
                            !data.surat_penerimaan
                        ) {

                            return `

                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm btn-upload-surat"
                                    data-id="${id}"
                                    data-kode="${data.kode_pengajuan}"
                                >

                                    <i class="bi bi-upload me-1"></i>

                                    Upload Surat

                                </button>

                            `;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Sudah ada surat
                        |--------------------------------------------------------------------------
                        */

                        const suratUrl =
                            '/storage/' +
                            data.surat_penerimaan;


                        return `

                            <div class="d-flex justify-content-center flex-wrap gap-1">

                                <a
                                    href="${suratUrl}"
                                    target="_blank"
                                    class="btn btn-success btn-sm"
                                    title="Lihat Surat"
                                >

                                    <i class="bi bi-eye"></i>

                                </a>


                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm btn-upload-surat"
                                    data-id="${id}"
                                    data-kode="${data.kode_pengajuan}"
                                    title="Ganti Surat"
                                >

                                    <i class="bi bi-arrow-repeat"></i>

                                </button>


                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm btn-hapus-surat"
                                    data-id="${id}"
                                    title="Hapus Surat"
                                >

                                    <i class="bi bi-trash"></i>

                                </button>

                            </div>

                            <div class="small text-muted mt-1">

                                <i class="bi bi-file-earmark-pdf me-1"></i>

                                Surat tersedia

                            </div>

                        `;

                    }

                }

            ],


            /*
            |--------------------------------------------------------------------------
            | Bahasa DataTable
            |--------------------------------------------------------------------------
            */

            language: {

                emptyTable:
                    'Belum ada kelompok peserta.',

                processing:
                    'Memuat data...',

                search:
                    'Cari:',

                lengthMenu:
                    'Tampilkan _MENU_ data',

                info:
                    'Menampilkan _START_ sampai _END_ dari _TOTAL_ kelompok',

                infoEmpty:
                    'Tidak ada data',

                zeroRecords:
                    'Kelompok tidak ditemukan.',

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
    | Buka Modal Upload
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btn-upload-surat',
        function () {

            selectedPengajuanId =
                $(this).data('id');


            const kode =
                $(this).data('kode');


            $('#pengajuan_id')
                .val(selectedPengajuanId);


            $('#kode_pengajuan_modal')
                .val(kode);


            $('#surat_penerimaan')
                .val('');


            modalSurat.show();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Upload Surat
    |--------------------------------------------------------------------------
    */

    $('#formSuratPenerimaan')
        .on('submit', function (e) {

            e.preventDefault();


            if (!selectedPengajuanId) {

                alert(
                    'Kelompok tidak ditemukan.'
                );

                return;

            }


            const formData =
                new FormData(this);


            const button =
                $('#btnUploadSurat');


            button
                .prop('disabled', true)
                .html(`
                    <span
                        class="spinner-border spinner-border-sm me-1"
                    ></span>

                    Mengupload...
                `);


            $.ajax({

                url:
                    '/back-office/peserta/' +
                    selectedPengajuanId +
                    '/surat-penerimaan',

                method: 'POST',

                data: formData,

                processData: false,

                contentType: false,

                headers: {

                    'X-CSRF-TOKEN':
                        csrfToken

                },


                success: function (response) {

                    modalSurat.hide();


                    $('#formSuratPenerimaan')[0]
                        .reset();


                    table.ajax.reload(
                        null,
                        false
                    );


                    alert(
                        response.message ??
                        'Surat penerimaan berhasil disimpan.'
                    );

                },


                error: function (xhr) {

                    let message =
                        'Gagal mengupload surat.';


                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.message
                    ) {

                        message =
                            xhr.responseJSON.message;

                    }


                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.errors
                    ) {

                        const errors =
                            xhr.responseJSON.errors;


                        const firstError =
                            Object.values(errors)[0];


                        if (firstError) {

                            message =
                                firstError[0];

                        }

                    }


                    alert(message);

                },


                complete: function () {

                    button
                        .prop('disabled', false)
                        .html(`
                            <i class="bi bi-upload me-1"></i>
                            Upload Surat
                        `);

                }

            });

        });


    /*
    |--------------------------------------------------------------------------
    | Klik Hapus Surat
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btn-hapus-surat',
        function () {

            selectedPengajuanId =
                $(this).data('id');


            modalHapus.show();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Konfirmasi Hapus
    |--------------------------------------------------------------------------
    */

    $('#btnHapusSurat')
        .on('click', function () {

            if (!selectedPengajuanId) {

                return;

            }


            const button =
                $(this);


            button
                .prop('disabled', true)
                .html(`
                    <span
                        class="spinner-border spinner-border-sm me-1"
                    ></span>

                    Menghapus...
                `);


            $.ajax({

                url:
                    '/back-office/peserta/' +
                    selectedPengajuanId +
                    '/surat-penerimaan',

                method: 'DELETE',

                headers: {

                    'X-CSRF-TOKEN':
                        csrfToken

                },


                success: function (response) {

                    modalHapus.hide();


                    table.ajax.reload(
                        null,
                        false
                    );


                    alert(
                        response.message ??
                        'Surat penerimaan berhasil dihapus.'
                    );

                },


                error: function (xhr) {

                    let message =
                        'Gagal menghapus surat.';


                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.message
                    ) {

                        message =
                            xhr.responseJSON.message;

                    }


                    alert(message);

                },


                complete: function () {

                    button
                        .prop('disabled', false)
                        .html(`
                            <i class="bi bi-trash me-1"></i>
                            Hapus Surat
                        `);

                }

            });

        });

});

</script>

@endpush