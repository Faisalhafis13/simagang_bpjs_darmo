@extends('layouts.back-office')

@section('title', 'Data Mentor')

@section('content')

{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

    <h3 class="fw-bold mb-1">
        Data Mentor
    </h3>

    <small class="text-muted">
        Kelola mentor dengan nama, divisi, dan penugasan saja.
    </small>

</div>


<div class="d-flex gap-2">

    <a
        href="{{ route('back-office.mentor.export') }}"
        class="btn btn-success"
    >
        <i class="bi bi-file-earmark-excel me-1"></i>
        Export Excel
    </a>

    <button
        type="button"
        class="btn btn-primary"
        id="btnTambahMentor"
    >
        <i class="bi bi-plus-circle me-1"></i>
        Tambah Mentor
    </button>

</div>
</div>

{{-- ========================================================= --}}
{{-- TABLE DATA MENTOR --}}
{{-- ========================================================= --}}

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body">

    <div class="table-responsive">

        <table
            id="mentorTable"
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
                        width="20%"
                    >
                        Nama Mentor
                    </th>


                    <th
                        width="18%"
                    >
                        Divisi
                    </th>


                    <th>
                        Nama Peserta
                    </th>


                    <th
                        width="15%"
                        class="text-center"
                    >
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

                <tr>

                    <td
                        colspan="5"
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
{{-- MODAL MENTOR --}}
{{-- ========================================================= --}}

<div
    class="modal fade"
    id="modalMentor"
    tabindex="-1"
    aria-hidden="true"
>

<div
    class="modal-dialog modal-lg modal-dialog-centered"
>

    <div
        class="modal-content border-0 shadow rounded-4"
    >

        <div class="modal-header">

            <div>

                <h5 class="modal-title fw-bold mb-1">
                    Data Mentor
                </h5>

            </div>


            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
            ></button>

        </div>


        <form id="formMentor">

            <div class="modal-body">

                <input
                    type="hidden"
                    id="mentorId"
                    name="mentor_id"
                >


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label
                            class="form-label fw-semibold"
                        >
                            Nama Mentor
                        </label>


                        <input
                            type="text"
                            class="form-control"
                            id="namaMentor"
                            name="nama_mentor"
                            required
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label
                            class="form-label fw-semibold"
                        >
                            Divisi
                        </label>


                        <input
                            type="text"
                            class="form-control"
                            id="divisi"
                            name="divisi"
                            required
                        >

                    </div>


                    <div class="col-12">

                        <label
                            class="form-label fw-bold"
                        >
                            Peserta Bimbingan
                        </label>


                        <div
                            id="listPeserta"
                            class="border rounded-3 p-3"
                            style="
                                max-height:300px;
                                overflow-y:auto;
                            "
                        >

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
                    id="btnSimpanMentor"
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

    const modalMentor =
        new bootstrap.Modal(
            document.getElementById('modalMentor')
        );

    let mentorTable;


    /*
    |--------------------------------------------------------------------------
    | HELPER SWEETALERT
    |--------------------------------------------------------------------------
    */

    function showError(message, title = 'Terjadi Kesalahan') {

        if (window.Swal) {

            Swal.fire({

                icon: 'error',

                title: title,

                text: message,

                confirmButtonText: 'OK'

            });

        } else {

            alert(message);

        }

    }


    function showSuccess(message) {

        if (window.Swal) {

            Swal.fire({

                icon: 'success',

                title: 'Berhasil',

                text: message,

                confirmButtonText: 'OK',

                timer: 1800,

                timerProgressBar: true

            });

        } else {

            alert(message);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    $(function () {

        mentorTable = $('#mentorTable').DataTable({

            destroy: true,

            processing: true,

            serverSide: false,

            ajax: {

                url: '/api/back-office/mentor',

                dataSrc: function (response) {

                    if (Array.isArray(response)) {

                        return response;

                    }


                    if (
                        response &&
                        Array.isArray(response.data)
                    ) {

                        return response.data;

                    }


                    return [];

                },


                error: function (
                    xhr,
                    status,
                    error
                ) {

                    console.error(
                        'DataTables AJAX error (mentor):',
                        status,
                        error,
                        xhr
                    );


                    const msg =
                        (
                            xhr &&
                            xhr.responseJSON &&
                            xhr.responseJSON.message
                        )
                            ? xhr.responseJSON.message
                            : 'Gagal memuat data mentor.';


                    showError(
                        msg,
                        'Gagal Memuat Data'
                    );


                    const tbody =
                        document.querySelector(
                            '#mentorTable tbody'
                        );


                    if (tbody) {

                        tbody.innerHTML = `

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted py-5"
                                >
                                    ${msg}
                                </td>

                            </tr>

                        `;

                    }

                }

            },


            columns: [

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


                {
                    data: 'nama_mentor',

                    render: function (data) {

                        return data || '-';

                    }

                },


                {
                    data: 'divisi',

                    render: function (data) {

                        return data || '-';

                    }

                },


                {
                    data: 'peserta_preview',

                    render: function (data) {

                        return data || '-';

                    }

                },


                {

                    data: null,

                    className: 'text-center',

                    orderable: false,

                    searchable: false,

                    render: function (data) {

                        return `

                            <div
                                class="d-flex justify-content-center flex-wrap gap-1"
                            >

                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm btn-edit"
                                    data-id="${data.id}"
                                    title="Edit Mentor"
                                >

                                    <i class="bi bi-pencil-square"></i>

                                </button>


                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm btn-delete"
                                    data-id="${data.id}"
                                    title="Hapus Mentor"
                                >

                                    <i class="bi bi-trash"></i>

                                </button>

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
                    'Belum ada data mentor.',

                processing:
                    'Memuat data...',

                search:
                    'Cari:',

                lengthMenu:
                    'Tampilkan _MENU_ data',

                info:
                    'Menampilkan _START_ sampai _END_ dari _TOTAL_ mentor',

                infoEmpty:
                    'Tidak ada data',

                zeroRecords:
                    'Mentor tidak ditemukan.',

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

            },


            autoWidth: false,

            scrollX: true,

            pageLength: 10,

            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ]

        });

    });


    /*
    |--------------------------------------------------------------------------
    | TAMBAH MENTOR
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('btnTambahMentor')
        .addEventListener(
            'click',
            async () => {

                document
                    .getElementById('formMentor')
                    .reset();


                document
                    .getElementById('mentorId')
                    .value = '';


                try {

                    const response =
                        await fetch(
                            '/api/back-office/mentor-peserta'
                        );


                    const result =
                        await response.json();


                    if (!response.ok) {

                        throw new Error(
                            result.message ||
                            'Gagal memuat data peserta.'
                        );

                    }


                    let html = '';


                    if (
                        result.data &&
                        Array.isArray(result.data)
                    ) {

                        result.data.forEach(
                            function (item) {

                                html += `

                                    <div class="form-check mb-2">

                                        <input
                                            class="form-check-input peserta-checkbox"
                                            type="checkbox"
                                            value="${item.id}"
                                            id="peserta${item.id}"
                                        >


                                        <label
                                            class="form-check-label"
                                            for="peserta${item.id}"
                                        >

                                            <strong>
                                                ${item.name}
                                            </strong>

                                            <br>

                                            <small class="text-muted">
                                                ${item.email}
                                            </small>

                                        </label>

                                    </div>

                                `;

                            }
                        );

                    } else {

                        html = `

                            <div
                                class="text-center text-muted"
                            >
                                Data peserta tidak ditemukan
                            </div>

                        `;

                    }


                    document
                        .getElementById('listPeserta')
                        .innerHTML = html;


                    modalMentor.show();


                } catch (error) {

                    console.error(error);


                    showError(
                        error.message ||
                        'Gagal memuat data peserta.',
                        'Gagal Memuat Peserta'
                    );

                }

            }
        );


    /*
    |--------------------------------------------------------------------------
    | SIMPAN / UPDATE MENTOR
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('formMentor')
        .addEventListener(
            'submit',
            async function (event) {

                event.preventDefault();


                const id =
                    document
                        .getElementById('mentorId')
                        .value;


                const url = id
                    ? `/api/back-office/mentor/${id}`
                    : '/api/back-office/mentor';


                const method = id
                    ? 'PUT'
                    : 'POST';


                const peserta = [];


                document
                    .querySelectorAll(
                        '.peserta-checkbox:checked'
                    )
                    .forEach(
                        function (item) {

                            peserta.push(
                                item.value
                            );

                        }
                    );


                const payload = {

                    _token:
                        document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )
                            .content,

                    nama_mentor:
                        document
                            .getElementById(
                                'namaMentor'
                            )
                            .value,

                    divisi:
                        document
                            .getElementById(
                                'divisi'
                            )
                            .value,

                    peserta: peserta

                };


                const btnSimpan =
                    document.getElementById(
                        'btnSimpanMentor'
                    );


                const originalText =
                    btnSimpan.innerHTML;


                /*
                |--------------------------------------------------------------------------
                | VALIDASI DASAR
                |--------------------------------------------------------------------------
                */

                if (!payload.nama_mentor.trim()) {

                    showError(
                        'Nama mentor wajib diisi.',
                        'Validasi'
                    );

                    return;

                }


                if (!payload.divisi.trim()) {

                    showError(
                        'Divisi wajib diisi.',
                        'Validasi'
                    );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | LOADING
                |--------------------------------------------------------------------------
                */

                btnSimpan.disabled = true;


                btnSimpan.innerHTML = `

                    <span
                        class="spinner-border spinner-border-sm me-1"
                        role="status"
                    ></span>

                    Menyimpan...

                `;


                try {

                    const response =
                        await fetch(
                            url,
                            {

                                method,

                                headers: {

                                    'Content-Type':
                                        'application/json',

                                    'Accept':
                                        'application/json'

                                },

                                body:
                                    JSON.stringify(
                                        payload
                                    )

                            }
                        );


                    const result =
                        await response.json();


                    if (response.ok) {

                        modalMentor.hide();


                        if (mentorTable) {

                            mentorTable.ajax.reload(
                                null,
                                false
                            );

                        }


                        showSuccess(
                            result.message ||
                            (
                                id
                                    ? 'Mentor berhasil diperbarui.'
                                    : 'Mentor berhasil ditambahkan.'
                            )
                        );


                        return;

                    }


                    let message =
                        result.message ||
                        'Terjadi kesalahan.';


                    if (result.errors) {

                        const errors =
                            Object.values(
                                result.errors
                            ).flat();


                        if (errors.length) {

                            message =
                                errors.join('\n');

                        }

                    }


                    showError(
                        message,
                        'Gagal Menyimpan'
                    );


                } catch (error) {

                    console.error(error);


                    showError(
                        'Tidak dapat terhubung ke server.',
                        'Koneksi Gagal'
                    );

                } finally {

                    btnSimpan.disabled = false;

                    btnSimpan.innerHTML =
                        originalText;

                }

            }
        );


    /*
    |--------------------------------------------------------------------------
    | EDIT & DELETE
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        async ({ target }) => {


            /*
            |--------------------------------------------------------------------------
            | EDIT
            |--------------------------------------------------------------------------
            */

            if (
                target.matches('.btn-edit') ||
                target.closest('.btn-edit')
            ) {

                const button =
                    target.closest('.btn-edit');


                const id =
                    button.dataset.id;


                try {

                    const response =
                        await fetch(
                            `/api/back-office/mentor/${id}`,
                            {
                                headers: {
                                    'Accept':
                                        'application/json'
                                }
                            }
                        );


                    const result =
                        await response.json();


                    if (!response.ok) {

                        throw new Error(
                            result.message ||
                            'Gagal mengambil data mentor.'
                        );

                    }


                    const mentor =
                        result.data.mentor;


                    const peserta =
                        result.data.peserta;


                    document
                        .getElementById('mentorId')
                        .value =
                        mentor.id;


                    document
                        .getElementById('namaMentor')
                        .value =
                        mentor.nama_mentor;


                    document
                        .getElementById('divisi')
                        .value =
                        mentor.divisi;


                    let html = '';


                    peserta.forEach(
                        function (item) {

                            const checked =
                                item.mentor_id ==
                                mentor.id
                                    ? 'checked'
                                    : '';


                            html += `

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input peserta-checkbox"
                                        type="checkbox"
                                        value="${item.id}"
                                        id="peserta${item.id}"
                                        ${checked}
                                    >


                                    <label
                                        class="form-check-label"
                                        for="peserta${item.id}"
                                    >

                                        <strong>
                                            ${item.name}
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            ${item.email}
                                        </small>

                                    </label>

                                </div>

                            `;

                        }
                    );


                    document
                        .getElementById('listPeserta')
                        .innerHTML = html;


                    modalMentor.show();


                } catch (error) {

                    console.error(error);


                    showError(
                        error.message ||
                        'Gagal memuat data mentor.',
                        'Gagal Memuat Data'
                    );

                }

            }


            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            if (
                target.matches('.btn-delete') ||
                target.closest('.btn-delete')
            ) {

                const button =
                    target.closest('.btn-delete');


                const id =
                    button.dataset.id;


                if (window.Swal) {

                    const confirmation =
                        await Swal.fire({

                            icon: 'warning',

                            title: 'Hapus Mentor?',

                            text:
                                'Data mentor akan dihapus. Tindakan ini tidak dapat dibatalkan.',

                            showCancelButton: true,

                            confirmButtonText:
                                'Ya, Hapus',

                            cancelButtonText:
                                'Batal',

                            reverseButtons: true,

                            focusCancel: true

                        });


                    if (!confirmation.isConfirmed) {

                        return;

                    }

                } else {

                    if (
                        !confirm(
                            'Hapus mentor ini?'
                        )
                    ) {

                        return;

                    }

                }


                if (window.Swal) {

                    Swal.fire({

                        title: 'Menghapus...',

                        text:
                            'Mohon tunggu sebentar.',

                        allowOutsideClick: false,

                        allowEscapeKey: false,

                        didOpen: () => {

                            Swal.showLoading();

                        }

                    });

                }


                try {

                    const response =
                        await fetch(
                            `/api/back-office/mentor/${id}`,
                            {

                                method: 'DELETE',

                                headers: {

                                    'Content-Type':
                                        'application/json',

                                    'Accept':
                                        'application/json'

                                },

                                body:
                                    JSON.stringify({

                                        _token:
                                            document
                                                .querySelector(
                                                    'meta[name="csrf-token"]'
                                                )
                                                .content

                                    })

                            }
                        );


                    const result =
                        await response.json();


                    if (response.ok) {

                        if (mentorTable) {

                            mentorTable.ajax.reload(
                                null,
                                false
                            );

                        }


                        showSuccess(
                            result.message ||
                            'Mentor berhasil dihapus.'
                        );


                        return;

                    }


                    showError(
                        result.message ||
                        'Mentor gagal dihapus.',
                        'Gagal Menghapus'
                    );


                } catch (error) {

                    console.error(error);


                    showError(
                        'Tidak dapat terhubung ke server.',
                        'Koneksi Gagal'
                    );

                }

            }

        }
    );

</script>

@endpush
