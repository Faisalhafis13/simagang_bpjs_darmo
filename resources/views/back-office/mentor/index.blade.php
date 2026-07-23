@extends('layouts.back-office')

@section('title','Data Mentor')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Data Mentor</h3>
            <small class="text-muted">Kelola mentor dengan nama, divisi, dan penugasan saja.</small>
        </div>
        <button class="btn btn-primary" id="btnTambahMentor">
            <i class="bi bi-plus-circle"></i> Tambah Mentor
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle w-100" id="mentorTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mentor</th>
                        <th>Divisi</th>
                        <th>Penugasan Yang Diberikan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<div class="modal fade" id="modalMentor" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4">
            <form id="formMentor">
                @csrf
                <input type="hidden" id="mentorId">
                <div class="modal-header">
                    <h5 class="modal-title">Data Mentor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Mentor</label>
                            <input type="text" class="form-control" id="namaMentor" name="nama_mentor">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Divisi</label>
                            <input type="text" class="form-control" id="divisi" name="divisi">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Tugas</label>
                            <textarea class="form-control" id="tugas" name="tugas" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    const modalMentor = new bootstrap.Modal(document.getElementById('modalMentor'));
    const mentorTableBody = document.querySelector('#mentorTable tbody');

    async function loadMentorData() {
        const response = await fetch('/api/back-office/mentor');
        const result = await response.json();

        mentorTableBody.innerHTML = '';

        if (result.status !== 'success' || !Array.isArray(result.data) || !result.data.length) {
            mentorTableBody.innerHTML = `
                <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada mentor.</td></tr>
            `;
            return;
        }

        result.data.forEach((mentor, index) => {
            mentorTableBody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${mentor.nama_mentor}</td>
                    <td>${mentor.divisi}</td>
                    <td>${mentor.tugas || '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-warning btn-edit" data-id="${mentor.id}">Edit</button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${mentor.id}">Hapus</button>
                    </td>
                </tr>
            `;
        });
    }

    document.getElementById('btnTambahMentor').addEventListener('click', () => {
        document.getElementById('formMentor').reset();
        document.getElementById('mentorId').value = '';
        modalMentor.show();
    });

    document.getElementById('formMentor').addEventListener('submit', async function (event) {
        event.preventDefault();

        const id = document.getElementById('mentorId').value;
        const url = id ? `/api/back-office/mentor/${id}` : '/api/back-office/mentor';
        const method = id ? 'PUT' : 'POST';

        const payload = {
            _token: document.querySelector('meta[name="csrf-token"]').content,
            nama_mentor: document.getElementById('namaMentor').value,
            divisi: document.getElementById('divisi').value,
            tugas: document.getElementById('tugas').value,
        };

        const response = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });
        const result = await response.json();

        if (response.ok) {
            modalMentor.hide();
            loadMentorData();
            alert(result.message || 'Data tersimpan.');
            return;
        }

        alert(result.message || 'Terjadi kesalahan.');
    });

    document.addEventListener('click', async ({ target }) => {
        if (target.matches('.btn-edit')) {
            const id = target.dataset.id;
            const response = await fetch(`/api/back-office/mentor/${id}`);
            const result = await response.json();
            const mentor = result.data;

            document.getElementById('mentorId').value = mentor.id;
            document.getElementById('namaMentor').value = mentor.nama_mentor;
            document.getElementById('divisi').value = mentor.divisi;
            document.getElementById('tugas').value = mentor.tugas || '';
            modalMentor.show();
        }

        if (target.matches('.btn-delete')) {
            const id = target.dataset.id;
            if (!confirm('Hapus mentor ini?')) return;
            const response = await fetch(`/api/back-office/mentor/${id}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ _token: document.querySelector('meta[name="csrf-token"]').content }),
            });
            const result = await response.json();
            if (response.ok) {
                loadMentorData();
                alert(result.message || 'Mentor dihapus.');
                return;
            }
            alert(result.message || 'Terjadi kesalahan.');
        }
    });

    loadMentorData();
</script>
@endpush
