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
                        <th>Nama Peserta</th>
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
        <input
            type="text"
            class="form-control"
            id="namaMentor"
            name="nama_mentor">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Divisi</label>
        <input
            type="text"
            class="form-control"
            id="divisi"
            name="divisi">
    </div>

    <div class="col-12">

        <label class="form-label fw-bold">
            Peserta Bimbingan
        </label>

        <div
            id="listPeserta"
            class="border rounded p-3"
            style="max-height:300px;overflow-y:auto;">

        </div>

    </div>

</div>                </div>
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
    let mentorTable;

    $(function(){

        mentorTable = $('#mentorTable').DataTable({

            destroy:true,

            processing:true,

            serverSide:false,

            ajax:{

                url:'/api/back-office/mentor',

                dataSrc:function(response){

                    if(Array.isArray(response)) return response;

                    if(response && Array.isArray(response.data)) return response.data;

                    return [];

                },

                error:function(xhr, status, error){

                    console.error('DataTables AJAX error (mentor):', status, error, xhr);

                    const msg = (xhr && xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal memuat data.';

                    if(window.Swal) Swal.fire({ icon:'error', title:'Gagal memuat', text: msg });

                    const tbody = document.querySelector('#mentorTable tbody');

                    if(tbody) tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">${msg}</td></tr>`;

                }

            },

            columns:[

                { data:null, render:function(data,type,row,meta){ return meta.row + 1; } },

                { data:'nama_mentor' },

                { data:'divisi' },

                { data:'peserta_preview', render:function(d){ return d || '-'; } },

                { data:null, orderable:false, searchable:false, render:function(data){

                    return `

                        <button class="btn btn-sm btn-warning btn-edit" data-id="${data.id}">Edit</button>

                        <button class="btn btn-sm btn-danger btn-delete" data-id="${data.id}">Hapus</button>

                    `;

                } }

            ]

        });

    });

document.getElementById('btnTambahMentor').addEventListener('click', async () => {

    document.getElementById('formMentor').reset();

    document.getElementById('mentorId').value = '';

    try {

        const response = await fetch('/api/back-office/mentor-peserta');

        const result = await response.json();

        console.log(result);

        let html = '';

        if (result.data && Array.isArray(result.data)) {

            result.data.forEach(function(item){

                html += `
                    <div class="form-check mb-2">

                        <input
                            class="form-check-input peserta-checkbox"
                            type="checkbox"
                            value="${item.id}"
                            id="peserta${item.id}">

                        <label
                            class="form-check-label"
                            for="peserta${item.id}">

                            <strong>${item.name}</strong><br>

                            <small class="text-muted">
                                ${item.email}
                            </small>

                        </label>

                    </div>
                `;

            });

        } else {

            html = `
                <div class="text-center text-muted">
                    Data peserta tidak ditemukan
                </div>
            `;

        }

        document.getElementById('listPeserta').innerHTML = html;

        modalMentor.show();

    } catch (error) {

        console.error(error);

        alert('Gagal memuat data peserta');

    }

});
    document.getElementById('formMentor').addEventListener('submit', async function (event) {
        event.preventDefault();

        const id = document.getElementById('mentorId').value;
        const url = id ? `/api/back-office/mentor/${id}` : '/api/back-office/mentor';
        const method = id ? 'PUT' : 'POST';

const peserta = [];

document.querySelectorAll('.peserta-checkbox:checked').forEach(function(item){

    peserta.push(item.value);

});

const payload = {

    _token: document.querySelector('meta[name="csrf-token"]').content,

    nama_mentor: document.getElementById('namaMentor').value,

    divisi: document.getElementById('divisi').value,

    peserta: peserta

};
        const response = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });
        const result = await response.json();

        if (response.ok) {
            modalMentor.hide();
            if(mentorTable) mentorTable.ajax.reload(null,false);
            alert(result.message || 'Data tersimpan.');
            return;
        }

        alert(result.message || 'Terjadi kesalahan.');
    });

document.addEventListener('click', async ({ target }) => {

    // =========================
    // EDIT
    // =========================
    if (target.matches('.btn-edit')) {

        const id = target.dataset.id;

        const response = await fetch(`/api/back-office/mentor/${id}`);

        const result = await response.json();

        const mentor = result.data.mentor;

        const peserta = result.data.peserta;

        document.getElementById('mentorId').value = mentor.id;

        document.getElementById('namaMentor').value = mentor.nama_mentor;

        document.getElementById('divisi').value = mentor.divisi;

        let html = '';

        peserta.forEach(function(item){

            const checked = item.mentor_id == mentor.id ? 'checked' : '';

            html += `
                <div class="form-check mb-2">

                    <input
                        class="form-check-input peserta-checkbox"
                        type="checkbox"
                        value="${item.id}"
                        id="peserta${item.id}"
                        ${checked}>

                    <label
                        class="form-check-label"
                        for="peserta${item.id}">

                        <strong>${item.name}</strong><br>

                        <small class="text-muted">
                            ${item.email}
                        </small>

                    </label>

                </div>
            `;

        });

        document.getElementById('listPeserta').innerHTML = html;

        modalMentor.show();

    }

    // =========================
    // DELETE
    // =========================
    if (target.matches('.btn-delete')) {

        const id = target.dataset.id;

        if (!confirm('Hapus mentor ini?')) return;

        const response = await fetch(`/api/back-office/mentor/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                _token: document.querySelector('meta[name="csrf-token"]').content
            }),
        });

        const result = await response.json();

        if (response.ok) {

            mentorTable.ajax.reload(null, false);

            alert(result.message);

            return;

        }

        alert(result.message || 'Terjadi kesalahan.');

    }

});
</script>
@endpush
