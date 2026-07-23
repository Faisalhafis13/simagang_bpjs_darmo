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

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editRoleMenuModal">Tambah</button>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Role</th>
                        <th>Menu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="roleMenuTableBody">
                    @foreach($roleMenus as $roleMenu)
                        <tr data-role-menu-id="{{ $roleMenu->id }}" data-role-id="{{ $roleMenu->role_id }}" data-menu-id="{{ $roleMenu->menu_id }}" data-status="{{ $roleMenu->status }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $roleMenu->role->name }}</td>
                            <td>{{ $roleMenu->menu->name }}</td>
                            <td>{{ ucfirst($roleMenu->status) }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary btn-edit-role-menu me-2" data-bs-toggle="modal" data-bs-target="#editRoleMenuModal">Edit</button>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-role-menu" data-role-menu-id="{{ $roleMenu->id }}">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
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
                        <select id="roleMenuMenu" class="form-select">
                            <option value="">Pilih menu</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="errorRoleMenuMenu"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="roleMenuStatus" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="invalid-feedback" id="errorRoleMenuStatus"></div>
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
        ['Role','Menu','Status'].forEach(field => {
            document.getElementById(`roleMenu${field}`).classList.remove('is-invalid');
            document.getElementById(`errorRoleMenu${field}`).textContent = '';
        });
    }

    document.querySelectorAll('.btn-edit-role-menu').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            document.getElementById('roleMenuId').value = row.dataset.roleMenuId;
            document.getElementById('roleMenuRole').value = row.dataset.roleId;
            document.getElementById('roleMenuMenu').value = row.dataset.menuId;
            document.getElementById('roleMenuStatus').value = row.dataset.status;
            resetRoleMenuErrors();
            document.getElementById('roleMenuModalAlert').innerHTML = '';
        });
    });

    document.getElementById('saveRoleMenuBtn').addEventListener('click', async function () {
        resetRoleMenuErrors();
        const id = document.getElementById('roleMenuId').value;
        const payload = {
            role_id: document.getElementById('roleMenuRole').value,
            menu_id: document.getElementById('roleMenuMenu').value,
            status: document.getElementById('roleMenuStatus').value,
        };

        const url = id ? `${roleMenuApiBase}/${id}` : roleMenuApiBase;
        const method = id ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const result = await response.json();

        if (!response.ok) {
            if (result.errors) {
                Object.entries(result.errors).forEach(([key, messages]) => {
                    const input = document.getElementById(`roleMenu${key.charAt(0).toUpperCase() + key.slice(1)}`);
                    const error = document.getElementById(`errorRoleMenu${key.charAt(0).toUpperCase() + key.slice(1)}`);
                    if (input && error) {
                        input.classList.add('is-invalid');
                        error.textContent = messages[0];
                    }
                });
            } else {
                showRoleMenuAlert('#roleMenuModalAlert', result.message || 'Terjadi kesalahan.', 'danger');
            }
            return;
        }

        showRoleMenuAlert('#alertPlaceholder', result.message, 'success');
        const modal = bootstrap.Modal.getInstance(document.getElementById('editRoleMenuModal'));
        modal.hide();
        window.location.reload();
    });

    document.querySelectorAll('.btn-delete-role-menu').forEach(button => {
        button.addEventListener('click', async function () {
            const id = this.dataset.roleMenuId;
            if (!confirm('Yakin ingin menghapus role menu ini?')) {
                return;
            }

            const response = await fetch(`${roleMenuApiBase}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                },
            });

            const result = await response.json();

            if (!response.ok) {
                showRoleMenuAlert('#alertPlaceholder', result.message || 'Gagal menghapus role menu.', 'danger');
                return;
            }

            showRoleMenuAlert('#alertPlaceholder', result.message, 'success');
            window.location.reload();
        });
    });

    document.getElementById('btnFilter').addEventListener('click', function () {
        const roleId = document.getElementById('filterRole').value;
        const menuId = document.getElementById('filterMenu').value;
        let url = "{{ url('back-office/role-menu') }}";
        const params = new URLSearchParams();
        if (roleId) params.append('role_id', roleId);
        if (menuId) params.append('menu_id', menuId);
        window.location.href = `${url}?${params.toString()}`;
    });
</script>
@endpush

@endsection
