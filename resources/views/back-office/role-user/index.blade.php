@extends('layouts.back-office')

@section('title', 'Role User')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
    <h3 class="fw-bold mb-1">
        Role User
    </h3>

    <small class="text-muted">
        Manajemen User dan Hak Akses
    </small>
</div>

<div class="d-flex gap-2">

    <a
        href="{{ route('back-office.role-user.export') }}"
        class="btn btn-success"
    >
        <i class="bi bi-file-earmark-excel me-1"></i>
        Export Excel
    </a>

    <button
        type="button"
        class="btn btn-primary"
        id="btnTambah"
    >
        <i class="bi bi-plus-circle me-1"></i>
        Tambah User
    </button>

</div>

</div>

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body">

    <div class="table-responsive">

        <table
            class="table table-bordered table-hover align-middle w-100"
            id="tableUser"
        >

            <thead class="table-light">

                <tr>

                    <th
                        width="5%"
                        class="text-center"
                    >
                        No
                    </th>

                    <th>
                        Nama
                    </th>

                    <th>
                        Email
                    </th>

                    <th>
                        Role
                    </th>

                    <th
                        width="15%"
                        class="text-center"
                    >
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
MODAL USER
========================================================= --}}

<div
    class="modal fade"
    id="modalUser"
    tabindex="-1"
    aria-labelledby="modalUserLabel"
    aria-hidden="true"
>

<div class="modal-dialog modal-lg modal-dialog-centered">

    <div class="modal-content border-0 rounded-4 shadow">

        <form id="formUser">

            @csrf

            <input
                type="hidden"
                id="id"
                name="id"
            >

            <div class="modal-header">

                <div>
                    <h5
                        class="modal-title fw-bold"
                        id="modalUserLabel"
                    >
                        Data User
                    </h5>

                    <small class="text-muted">
                        Kelola informasi user dan role akses.
                    </small>
                </div>

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
                        for="name"
                        class="form-label fw-semibold"
                    >
                        Nama
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        name="name"
                        placeholder="Masukkan nama user"
                        autocomplete="name"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label
                        for="email"
                        class="form-label fw-semibold"
                    >
                        Email
                    </label>

                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder="Masukkan email user"
                        autocomplete="email"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label
                        for="password"
                        class="form-label fw-semibold"
                    >
                        Password
                    </label>

                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="new-password"
                    >

                    <small class="text-muted">
                        Kosongkan jika tidak ingin mengubah password.
                    </small>

                </div>


                <div class="mb-3">

                    <label
                        for="role_id"
                        class="form-label fw-semibold"
                    >
                        Role
                    </label>

                    <select
                        class="form-select"
                        id="role_id"
                        name="role_id"
                        required
                    >

                        <option value="">
                            -- Pilih Role --
                        </option>

                        @foreach($roles as $role)

                            <option value="{{ $role->id }}">
                                {{ $role->name }}
                            </option>

                        @endforeach

                    </select>

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

let modalUser;
let tableUser;


/*
|--------------------------------------------------------------------------
| DOCUMENT READY
|--------------------------------------------------------------------------
*/

$(function () {

    modalUser = new bootstrap.Modal(
        document.getElementById('modalUser')
    );

    initTable();

});


/*
|--------------------------------------------------------------------------
| DATATABLE
|--------------------------------------------------------------------------
*/

function initTable()
{

    if ($.fn.DataTable.isDataTable('#tableUser')) {

        $('#tableUser')
            .DataTable()
            .destroy();

    }


    tableUser = $('#tableUser').DataTable({

        processing: true,

        responsive: false,

        autoWidth: false,

        ajax: {

            url: '/back-office/role-user/data',

            type: 'GET',

            dataSrc: function (response) {

                if (
                    !response ||
                    response.status === 'error'
                ) {
                    return [];
                }

                return response.data || [];

            },

            error: function (xhr) {

                console.error(
                    'Gagal mengambil data user:',
                    xhr.responseText
                );

                Swal.fire({

                    icon: 'error',

                    title: 'Gagal Memuat Data',

                    text:
                        xhr.responseJSON?.message ||
                        'Data user tidak dapat dimuat.'

                });

            }

        },


        columns: [

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

                    return meta.row + 1;

                }

            },


            {
                data: 'name',

                defaultContent: '-',

                render: function (data) {

                    return escapeHtml(data || '-');

                }

            },


            {
                data: 'email',

                defaultContent: '-',

                render: function (data) {

                    return escapeHtml(data || '-');

                }

            },


            {
                data: 'role',

                defaultContent: null,

                render: function (data) {

                    if (!data || !data.name) {

                        return `
                            <span class="text-muted">
                                Tidak ada role
                            </span>
                        `;

                    }

                    return `
                        <span class="badge bg-primary">
                            ${escapeHtml(data.name)}
                        </span>
                    `;

                }

            },


            {
                data: null,

                className: 'text-center',

                orderable: false,

                searchable: false,

                render: function (data) {

                    return `

                        <div class="d-flex justify-content-center gap-1">

                            <button
                                type="button"
                                class="btn btn-sm btn-warning btn-edit"
                                data-id="${escapeAttribute(data.id)}"
                                title="Edit user"
                            >
                                <i class="bi bi-pencil-square"></i>
                               
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-danger btn-delete"
                                data-id="${escapeAttribute(data.id)}"
                                title="Hapus user"
                            >
                                <i class="bi bi-trash"></i>
                             
                            </button>

                        </div>

                    `;

                }

            }

        ],


        order: [
            [1, 'asc']
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
                'Data user tidak ditemukan',

            emptyTable:
                'Belum ada data user',

            paginate: {

                first: 'Pertama',

                last: 'Terakhir',

                next: '›',

                previous: '‹'

            }

        }

    });

}


/*
|--------------------------------------------------------------------------
| TAMBAH USER
|--------------------------------------------------------------------------
*/

$('#btnTambah').on('click', function () {

    resetForm();

    $('#modalUserLabel').text(
        'Tambah User'
    );

    $('#btnSimpan').html(`
        <i class="bi bi-save me-1"></i>
        Simpan
    `);

    modalUser.show();

});


/*
|--------------------------------------------------------------------------
| RESET FORM
|--------------------------------------------------------------------------
*/

function resetForm()
{

    const form = document.getElementById(
        'formUser'
    );

    form.reset();

    $('#id').val('');

    $('#role_id').val('');

}


/*
|--------------------------------------------------------------------------
| SUBMIT FORM
|--------------------------------------------------------------------------
*/

$('#formUser').on(
    'submit',
    async function (event) {

        event.preventDefault();


        const id = $('#id').val();


        const url = id

            ? `/back-office/role-user/${id}`

            : '/back-office/role-user';


        const method = id
            ? 'PUT'
            : 'POST';


        const csrfToken = $(
            'meta[name="csrf-token"]'
        ).attr('content');


        const payload = {

            _token: csrfToken,

            name: $('#name').val().trim(),

            email: $('#email').val().trim(),

            role_id: $('#role_id').val()

        };


        const password =
            $('#password').val();


        if (password) {

            payload.password = password;

        }


        $('#btnSimpan')
            .prop('disabled', true)
            .html(`
                <span
                    class="spinner-border spinner-border-sm me-1"
                    role="status"
                ></span>
                Menyimpan...
            `);


        try {

            const response = await fetch(
                url,
                {

                    method: method,

                    headers: {

                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'X-CSRF-TOKEN':
                            csrfToken

                    },

                    body: JSON.stringify(
                        payload
                    )

                }
            );


            const result =
                await response.json();


            if (!response.ok) {

                throw new Error(
                    result.message ||
                    'Data user gagal disimpan.'
                );

            }


            modalUser.hide();


            tableUser
                .ajax
                .reload(null, false);


            Swal.fire({

                icon: 'success',

                title: 'Berhasil',

                text:
                    result.message ||
                    'Data user berhasil disimpan.',

                timer: 1500,

                showConfirmButton: false

            });


        } catch (error) {

            console.error(error);


            Swal.fire({

                icon: 'error',

                title: 'Gagal',

                text:
                    error.message ||
                    'Terjadi kesalahan saat menyimpan data.'

            });

        } finally {

            $('#btnSimpan')
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
| EDIT USER
|--------------------------------------------------------------------------
*/

$(document).on(
    'click',
    '.btn-edit',
    async function () {

        const id = $(this).data('id');


        try {

            const response = await fetch(
                `/back-office/role-user/data/${id}`,
                {

                    method: 'GET',

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
                    'Gagal mengambil data user.'
                );

            }


            const data =
                result.data || result;


            $('#id').val(data.id);

            $('#name').val(
                data.name || ''
            );

            $('#email').val(
                data.email || ''
            );

            $('#password').val('');

            $('#role_id').val(
                data.role_id || ''
            );


            $('#modalUserLabel').text(
                'Edit User'
            );


            $('#btnSimpan').html(`
                <i class="bi bi-save me-1"></i>
                Update
            `);


            modalUser.show();


        } catch (error) {

            console.error(error);


            Swal.fire({

                icon: 'error',

                title: 'Gagal',

                text:
                    error.message ||
                    'Gagal mengambil data user.'

            });

        }

    }
);


/*
|--------------------------------------------------------------------------
| HAPUS USER
|--------------------------------------------------------------------------
*/

$(document).on(
    'click',
    '.btn-delete',
    async function () {

        const id = $(this).data('id');


        const confirmation =
            await Swal.fire({

                icon: 'warning',

                title: 'Hapus User?',

                text:
                    'Data user yang dihapus tidak dapat dikembalikan.',

                showCancelButton: true,

                confirmButtonText:
                    'Ya, Hapus',

                cancelButtonText:
                    'Batal',

                reverseButtons: true

            });


        if (!confirmation.isConfirmed) {

            return;

        }


        try {

            const csrfToken = $(
                'meta[name="csrf-token"]'
            ).attr('content');


            const response = await fetch(
                `/back-office/role-user/${id}`,
                {

                    method: 'DELETE',

                    headers: {

                        'Accept':
                            'application/json',

                        'X-CSRF-TOKEN':
                            csrfToken

                    }

                }
            );


            const result =
                await response.json();


            if (!response.ok) {

                throw new Error(
                    result.message ||
                    'Gagal menghapus user.'
                );

            }


            tableUser
                .ajax
                .reload(null, false);


            Swal.fire({

                icon: 'success',

                title: 'Berhasil',

                text:
                    result.message ||
                    'User berhasil dihapus.',

                timer: 1300,

                showConfirmButton: false

            });


        } catch (error) {

            console.error(error);


            Swal.fire({

                icon: 'error',

                title: 'Gagal',

                text:
                    error.message ||
                    'Terjadi kesalahan saat menghapus user.'

            });

        }

    }
);


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

    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');

}

</script>

@endpush
