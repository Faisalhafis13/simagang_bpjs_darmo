@extends('layouts.back-office')

@section('title', 'Role Menu')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            Role Menu
        </h3>

        <small class="text-muted">
            Pengaturan menu yang dapat diakses oleh setiap role.
        </small>
    </div>

    <button
        type="button"
        class="btn btn-primary"
        id="btnTambahRoleMenu"
    >
        <i class="bi bi-plus-circle"></i>
        Atur Role Menu
    </button>

</div>


<div id="alertPlaceholder"></div>


<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body">

        <div class="table-responsive">

            <table
                class="table table-bordered table-hover align-middle w-100"
                id="roleMenuTable"
            >

                <thead class="table-light">

                    <tr>
                        <th width="5%" class="text-center">
                            No
                        </th>

                        <th>
                            Role
                        </th>

                        <th>
                            Menu Yang Bisa Diakses
                        </th>

                        <th class="text-center">
                            Status
                        </th>

                        <th width="10%" class="text-center">
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
     MODAL ROLE MENU
========================================================= --}}

<div
    class="modal fade"
    id="editRoleMenuModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow">

            <div class="modal-header">

                <h5 class="modal-title fw-bold">
                    Pengaturan Role Menu
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <div class="modal-body">

                <div id="roleMenuModalAlert"></div>


                <form id="roleMenuForm">

                    @csrf

                    <input
                        type="hidden"
                        id="roleMenuId"
                    >


                    {{-- ROLE --}}

                    <div class="mb-3">

                        <label
                            for="roleMenuRole"
                            class="form-label fw-bold"
                        >
                            Role
                        </label>

                        <select
                            id="roleMenuRole"
                            class="form-select"
                        >

                            <option value="">
                                Pilih role
                            </option>

                            @foreach($roles as $role)

                                <option value="{{ $role->id }}">
                                    {{ $role->name }}
                                </option>

                            @endforeach

                        </select>

                        <div
                            class="invalid-feedback"
                            id="errorRoleMenuRole"
                        ></div>

                    </div>


                    {{-- MENU --}}

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Menu
                        </label>

                        <div
                            id="roleMenuMenusContainer"
                            class="border rounded-3 p-3"
                            style="max-height: 350px; overflow-y: auto;"
                        >

                            @forelse($menus as $menu)

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input role-menu-checkbox"
                                        type="checkbox"
                                        id="roleMenuCheckbox{{ $menu->id }}"
                                        value="{{ $menu->id }}"
                                        data-menu-id="{{ $menu->id }}"
                                    >

                                    <label
                                        class="form-check-label"
                                        for="roleMenuCheckbox{{ $menu->id }}"
                                    >
                                        {{ $menu->name }}
                                    </label>

                                </div>

                            @empty

                                <div class="text-muted">
                                    Belum ada menu tersedia.
                                </div>

                            @endforelse

                        </div>

                        <div
                            class="text-danger small mt-1"
                            id="errorRoleMenuMenu"
                        ></div>

                    </div>

                </form>

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
                    class="btn btn-primary"
                    id="saveRoleMenuBtn"
                >
                    <i class="bi bi-save"></i>
                    Simpan
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
    | CONFIG
    |--------------------------------------------------------------------------
    */

    const roleMenuApiBase =
        @json(url('api/back-office/role-menu'));

    const csrfToken =
        $('meta[name="csrf-token"]').attr('content');


    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        return $('<div>')
            .text(value ?? '')
            .html();

    }


    function showRoleMenuAlert(
        target,
        message,
        type = 'success'
    ) {

        $(target).html(`
            <div
                class="alert alert-${type} alert-dismissible fade show"
                role="alert"
            >
                ${escapeHtml(message)}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                ></button>

            </div>
        `);

    }


    function resetRoleMenuErrors() {

        $('#roleMenuRole')
            .removeClass('is-invalid');

        $('#errorRoleMenuRole')
            .text('');

        $('#errorRoleMenuMenu')
            .text('');

    }


    function resetRoleMenuForm() {

        $('#roleMenuForm')[0].reset();

        $('#roleMenuId').val('');

        $('#roleMenuRole')
            .prop('disabled', false);

        $('.role-menu-checkbox').each(function () {

            $(this)
                .prop('checked', false)
                .removeAttr('data-role-menu-id')
                .removeAttr('data-original-status');

        });

        resetRoleMenuErrors();

        $('#roleMenuModalAlert').html('');

    }


    /*
    |--------------------------------------------------------------------------
    | MODAL
    |--------------------------------------------------------------------------
    */

    const roleMenuModalElement =
        document.getElementById('editRoleMenuModal');

    const roleMenuModal =
        bootstrap.Modal.getOrCreateInstance(
            roleMenuModalElement
        );


    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    const roleMenuTable =
        $('#roleMenuTable').DataTable({

            processing: true,

            autoWidth: false,

            ajax: {

                url: roleMenuApiBase,

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
                        'Gagal memuat role menu:',
                        xhr.responseText
                    );

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
                    data: 'role',

                    defaultContent: '-',

                    render: function (data) {

                        if (
                            typeof data === 'object' &&
                            data !== null
                        ) {

                            return escapeHtml(
                                data.name || '-'
                            );

                        }

                        return escapeHtml(
                            data || '-'
                        );

                    }

                },


                {
                    data: 'menus',

                    orderable: false,

                    render: function (data) {

                        if (
                            !Array.isArray(data) ||
                            data.length === 0
                        ) {

                            return `
                                <span class="text-muted">
                                    Belum ada menu
                                </span>
                            `;

                        }


                        let html = `
                            <div class="d-flex flex-wrap gap-1">
                        `;


                        data.forEach(function (menu) {

                            const active =
                                String(
                                    menu.status || ''
                                ).toLowerCase() === 'active';


                            html += `

                                <span
                                    class="badge ${
                                        active
                                            ? 'bg-primary'
                                            : 'bg-secondary'
                                    }"
                                >
                                    ${escapeHtml(
                                        menu.name || '-'
                                    )}
                                </span>

                            `;

                        });


                        html += `
                            </div>
                        `;


                        return html;

                    }

                },


                {
                    data: 'status',

                    className: 'text-center',

                    render: function (data) {

                        const status =
                            String(
                                data || ''
                            ).toLowerCase();


                        if (status === 'active') {

                            return `
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i>
                                    Active
                                </span>
                            `;

                        }


                        return `
                            <span class="badge bg-secondary">
                                <i class="bi bi-x-circle"></i>
                                Inactive
                            </span>
                        `;

                    }

                },


                {
                    data: null,

                    className: 'text-center',

                    orderable: false,

                    searchable: false,

                    render: function (
                        data,
                        type,
                        row
                    ) {

                        return `

                            <button
                                type="button"
                                class="btn btn-sm btn-warning btn-edit-role-menu"
                                data-role="${escapeHtml(row.id)}"
                                title="Atur menu"
                            >
                                <i class="bi bi-pencil-square"></i>
                            </button>

                        `;

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
                    'Data tidak ditemukan',

                emptyTable:
                    'Belum ada data role menu',

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
    | TAMBAH / ATUR ROLE MENU
    |--------------------------------------------------------------------------
    */

    $('#btnTambahRoleMenu').on(
        'click',
        function () {

            resetRoleMenuForm();

            roleMenuModal.show();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EDIT ROLE MENU
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btn-edit-role-menu',
        function () {

            const roleId =
                $(this).data('role');


            const rows =
                roleMenuTable
                    .rows()
                    .data()
                    .toArray();


            const row =
                rows.find(function (item) {

                    return String(item.id) ===
                        String(roleId);

                });


            if (!row) {

                showRoleMenuAlert(
                    '#alertPlaceholder',
                    'Data role tidak ditemukan.',
                    'danger'
                );

                return;

            }


            resetRoleMenuForm();


            $('#roleMenuId')
                .val(roleId);


            $('#roleMenuRole')
                .val(roleId)
                .prop('disabled', true);


            if (
                Array.isArray(row.menus)
            ) {

                row.menus.forEach(
                    function (menu) {

                        const checkbox =
                            $(
                                `#roleMenuCheckbox${menu.menu_id}`
                            );


                        if (
                            checkbox.length
                        ) {

                            const active =
                                String(
                                    menu.status || ''
                                ).toLowerCase() ===
                                'active';


                            checkbox
                                .prop(
                                    'checked',
                                    active
                                )
                                .attr(
                                    'data-role-menu-id',
                                    menu.role_menu_id || ''
                                )
                                .attr(
                                    'data-original-status',
                                    menu.status || ''
                                );

                        }

                    }
                );

            }


            roleMenuModal.show();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SIMPAN
    |--------------------------------------------------------------------------
    */

    $('#saveRoleMenuBtn').on(
        'click',
        async function () {

            resetRoleMenuErrors();


            const button =
                $(this);


            const roleId =
                $('#roleMenuRole').val();


            if (!roleId) {

                $('#roleMenuRole')
                    .addClass('is-invalid');

                $('#errorRoleMenuRole')
                    .text(
                        'Role harus dipilih.'
                    );

                return;

            }


            const checkedCount =
                $('.role-menu-checkbox:checked')
                    .length;


            if (checkedCount === 0) {

                $('#errorRoleMenuMenu')
                    .text(
                        'Minimal satu menu harus dipilih.'
                    );

                return;

            }


            button
                .prop('disabled', true)
                .html(`
                    <span
                        class="spinner-border spinner-border-sm me-1"
                    ></span>
                    Menyimpan...
                `);


            try {

                const promises = [];


                $('.role-menu-checkbox')
                    .each(function () {

                        const checkbox = this;

                        const menuId =
                            checkbox.value;

                        const roleMenuId =
                            checkbox.getAttribute(
                                'data-role-menu-id'
                            );

                        const originalStatus =
                            (
                                checkbox.getAttribute(
                                    'data-original-status'
                                ) || ''
                            ).toLowerCase();

                        const checked =
                            checkbox.checked;


                        /*
                        |--------------------------------------------------------------------------
                        | SUDAH ADA
                        |--------------------------------------------------------------------------
                        */

                        if (roleMenuId) {

                            /*
                            | Sudah aktif dan tetap dicentang
                            | Tidak perlu request.
                            */

                            if (
                                checked &&
                                originalStatus === 'active'
                            ) {

                                return;

                            }


                            /*
                            | Ada tetapi ingin diaktifkan
                            */

                            if (checked) {

                                promises.push(
                                    fetch(
                                        `${roleMenuApiBase}/${roleMenuId}`,
                                        {

                                            method: 'PUT',

                                            credentials:
                                                'same-origin',

                                            headers: {

                                                'Content-Type':
                                                    'application/json',

                                                'Accept':
                                                    'application/json',

                                                'X-CSRF-TOKEN':
                                                    csrfToken

                                            },

                                            body:
                                                JSON.stringify({
                                                    role_id:
                                                        roleId,

                                                    menu_id:
                                                        menuId,

                                                    status:
                                                        'active'
                                                })

                                        }
                                    ).then(
                                        async function (
                                            response
                                        ) {

                                            const data =
                                                await response
                                                    .json()
                                                    .catch(
                                                        () => ({})
                                                    );

                                            return {
                                                ok:
                                                    response.ok,

                                                data
                                            };

                                        }
                                    )
                                );

                                return;

                            }


                            /*
                            | Ada tetapi dicentang menjadi
                            | tidak dicentang => hapus.
                            */

                            promises.push(
                                fetch(
                                    `${roleMenuApiBase}/${roleMenuId}`,
                                    {

                                        method: 'DELETE',

                                        credentials:
                                            'same-origin',

                                        headers: {

                                            'Accept':
                                                'application/json',

                                            'X-CSRF-TOKEN':
                                                csrfToken

                                        }

                                    }
                                ).then(
                                    async function (
                                        response
                                    ) {

                                        const data =
                                            await response
                                                .json()
                                                .catch(
                                                    () => ({})
                                                );

                                        return {
                                            ok:
                                                response.ok,

                                            data
                                        };

                                    }
                                )
                            );

                            return;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | BELUM ADA
                        |--------------------------------------------------------------------------
                        */

                        if (checked) {

                            promises.push(
                                fetch(
                                    roleMenuApiBase,
                                    {

                                        method: 'POST',

                                        credentials:
                                            'same-origin',

                                        headers: {

                                            'Content-Type':
                                                'application/json',

                                            'Accept':
                                                'application/json',

                                            'X-CSRF-TOKEN':
                                                csrfToken

                                        },

                                        body:
                                            JSON.stringify({
                                                role_id:
                                                    roleId,

                                                menu_id:
                                                    menuId,

                                                status:
                                                    'active'
                                            })

                                    }
                                ).then(
                                    async function (
                                        response
                                    ) {

                                        const data =
                                            await response
                                                .json()
                                                .catch(
                                                    () => ({})
                                                );

                                        return {
                                            ok:
                                                response.ok,

                                            data
                                        };

                                    }
                                )
                            );

                        }

                    });


                const results =
                    await Promise.all(
                        promises
                    );


                const failed =
                    results.find(
                        result => !result.ok
                    );


                if (failed) {

                    throw new Error(
                        failed.data?.message ||
                        'Terjadi kesalahan saat menyimpan role menu.'
                    );

                }


                roleMenuModal.hide();


                roleMenuTable
                    .ajax
                    .reload(
                        null,
                        false
                    );


                Swal.fire({

                    icon: 'success',

                    title: 'Berhasil',

                    text:
                        'Perubahan role menu berhasil disimpan.',

                    timer: 1500,

                    showConfirmButton: false

                });

            } catch (error) {

                console.error(error);


                showRoleMenuAlert(
                    '#roleMenuModalAlert',
                    error.message ||
                        'Terjadi kesalahan saat menyimpan.',
                    'danger'
                );

            } finally {

                button
                    .prop('disabled', false)
                    .html(`
                        <i class="bi bi-save"></i>
                        Simpan
                    `);

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | RESET MODAL SAAT DITUTUP
    |--------------------------------------------------------------------------
    */

    $('#editRoleMenuModal').on(
        'hidden.bs.modal',
        function () {

            resetRoleMenuForm();

        }
    );

});

</script>

@endpush
