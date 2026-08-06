@extends('layouts.back-office')

@section('title','Role Menu')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold mb-1">Role Menu</h3>
        <p class="text-muted mb-0">Kelola relasi role dan menu serta status aksesnya.</p>
    </div>
</div>

<div id="alertPlaceholder"></div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-success" id="btnTambahRoleMenu">
                <i class="bi bi-plus-circle"></i>
                Tambah
            </button>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle w-100" id="roleMenuTable">
                <thead class="table-light">
<tr>

    <th>No</th>

    <th>Role</th>

    <th>Menu Yang Bisa Diakses</th>

    <th>Status</th>

    <th>Aksi</th>

</tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="editRoleMenuModal" tabindex="-1" aria-labelledby="editRoleMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleMenuModalLabel">Tambah / Edit Role Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="roleMenuModalAlert"></div>
                <form id="roleMenuForm">
                    <input type="hidden" id="roleMenuId" value="">

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select id="roleMenuRole" class="form-select">
                            <option value="">Pilih role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="errorRoleMenuRole"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Menu</label>
                        <div id="roleMenuMenusContainer">
                            @foreach($menus as $menu)
                                <div class="form-check">
                                    <input
                                        class="form-check-input role-menu-checkbox"
                                        type="checkbox"
                                        id="roleMenuCheckbox{{ $menu->id }}"
                                        value="{{ $menu->id }}"
                                        data-menu-id="{{ $menu->id }}">

                                    <label class="form-check-label" for="roleMenuCheckbox{{ $menu->id }}">
                                        {{ $menu->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="invalid-feedback d-block" id="errorRoleMenuMenu"></div>
                    </div>

                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveRoleMenuBtn">Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    const roleMenuApiBase = '{{ url("api/back-office/role-menu") }}';

    function showRoleMenuAlert(target, message, type = 'success') {
        document.querySelector(target).innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }

    function resetRoleMenuErrors() {
        ['Role', 'Menu'].forEach(field => {
            const input = document.getElementById(`roleMenu${field}`);
            const error = document.getElementById(`errorRoleMenu${field}`);
            if (input) input.classList.remove('is-invalid');
            if (error) error.textContent = '';
        });
    }

    const roleMenuTable = $('#roleMenuTable').DataTable({
        destroy: true,
        processing: true,
        ajax: {
            url: roleMenuApiBase,
            dataSrc: function(response) {
                return response.data || [];
            }
        },
columns: [

{
    data: null,
    render: function(data, type, row, meta){
        return meta.row + 1;
    }
},

{
    data: 'role'
},

{
    data: 'menus',
    render: function(data){

        if(data.length==0){
            return '<span class="text-muted">Belum ada menu</span>';
        }

        let html='';

        data.forEach(function(menu){

            html += `
            <div class="form-check">

                <input
                    class="form-check-input"
                    type="checkbox"
                    ${menu.status=='active'?'checked':''}
                    disabled>

                <label class="form-check-label">

                    ${menu.name}

                </label>

            </div>
            `;

        });

        return html;

    }
},

{
    data:'status',
    render:function(data){

        if(data=='active'){

            return `<span class="badge bg-success">
                        Active
                    </span>`;

        }

        return `<span class="badge bg-secondary">
                    Inactive
                </span>`;

    }
},

{
    data: 'menus',
    orderable: false,
    searchable: false,
    render: function(data, type, row) {
        return `
            <button class="btn btn-warning btn-sm btn-edit-role-menu" data-role="${row.id}">
                <i class="bi bi-pencil"></i>
            </button>
        `;
    }
}
]
    });

    const roleMenuModal = new bootstrap.Modal(document.getElementById('editRoleMenuModal'));

    $('#btnTambahRoleMenu').on('click', function() {
        $('#roleMenuForm')[0].reset();
        $('#roleMenuId').val('');
        // clear checkboxes
        $('.role-menu-checkbox').each(function() {
            $(this).prop('checked', false);
            $(this).attr('data-role-menu-id', '');
            $(this).attr('data-original-status', '');
        });
        resetRoleMenuErrors();
        $('#roleMenuModalAlert').html('');
        roleMenuModal.show();
    });

$(document).on('click', '.btn-edit-role-menu', function() {

    const roleId = $(this).data('role');

    const rows = roleMenuTable.rows().data().toArray();
    const row = rows.find(r => r.id == roleId);

    if (!row) {
        showRoleMenuAlert('#alertPlaceholder', 'Data role tidak ditemukan.', 'danger');
        return;
    }

    // reset form
    $('#roleMenuForm')[0].reset();
    $('#roleMenuId').val('');
    $('#roleMenuRole').val(roleId);

    // clear checkboxes and data attributes
    $('.role-menu-checkbox').each(function() {
        $(this).prop('checked', false);
        $(this).attr('data-role-menu-id', '');
        $(this).attr('data-original-status', '');
    });

    // populate checkboxes from row.menus
    row.menus.forEach(function(menu) {
        const checkbox = $(`#roleMenuCheckbox${menu.menu_id}`);
        if (checkbox.length) {
            checkbox.prop('checked', menu.status === 'active');
            checkbox.attr('data-role-menu-id', menu.role_menu_id);
            checkbox.attr('data-original-status', menu.status);
        }
    });

    resetRoleMenuErrors();
    $('#roleMenuModalAlert').html('');
    roleMenuModal.show();

});
    $('#saveRoleMenuBtn').on('click', async function() {
        resetRoleMenuErrors();

        const roleId = $('#roleMenuRole').val();
        if (!roleId) {
            $('#roleMenuRole').addClass('is-invalid');
            $('#errorRoleMenuRole').text('Role harus dipilih.');
            return;
        }

        const checkboxes = $('.role-menu-checkbox');
        const promises = [];

        checkboxes.each(function() {
            const cb = this;
            const menuId = cb.value;
            const roleMenuId = cb.getAttribute('data-role-menu-id');
            const originalStatus = cb.getAttribute('data-original-status') || '';
            const checked = cb.checked;

            if (roleMenuId) {
                if (checked) {
                    if (originalStatus !== 'active') {
                        const payload = { role_id: roleId, menu_id: menuId, status: 'active' };
                        promises.push(fetch(`${roleMenuApiBase}/${roleMenuId}`, {
                            method: 'PUT',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify(payload),
                        }).then(r => r.json().then(data => ({ ok: r.ok, data }))));
                    }
                } else {
                    // unchecked => delete existing
                    promises.push(fetch(`${roleMenuApiBase}/${roleMenuId}`, {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    }).then(r => r.json().then(data => ({ ok: r.ok, data }))));
                }
            } else {
                if (checked) {
                    const payload = { role_id: roleId, menu_id: menuId, status: 'active' };
                    promises.push(fetch(roleMenuApiBase, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify(payload),
                    }).then(r => r.json().then(data => ({ ok: r.ok, data }))));
                }
            }
        });

        const results = await Promise.all(promises);

        const errors = results.filter(r => !r.ok);
        if (errors.length) {
            showRoleMenuAlert('#roleMenuModalAlert', errors[0].data.message || 'Terjadi kesalahan saat menyimpan.', 'danger');
            return;
        }

        showRoleMenuAlert('#alertPlaceholder', 'Perubahan role menu berhasil disimpan.', 'success');
        roleMenuModal.hide();
        $('#roleMenuForm')[0].reset();
        $('#roleMenuId').val('');
        roleMenuTable.ajax.reload();
    });

    $(document).on('click', '.btn-delete-role-menu', async function() {
        const id = $(this).data('id');
        if (!confirm('Yakin ingin menghapus role menu ini?')) {
            return;
        }

        const response = await fetch(`${roleMenuApiBase}/${id}`, {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        const result = await response.json();

        if (!response.ok) {
            showRoleMenuAlert('#alertPlaceholder', result.message || 'Gagal menghapus role menu.', 'danger');
            return;
        }

        showRoleMenuAlert('#alertPlaceholder', result.message, 'success');
roleMenuTable.ajax.reload();
    });
</script>
@endpush

@endsection
